<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        // Check permission to view users
        if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        
        $query = User::select('*');
        
        // When logged in as an Employee, only show Customer users
        if(auth()->user()->hasRole('Employee')) {
            // Filter to only show users with Customer role
            $customerRoleId = Role::where('name', 'Customer')->first()->id;
            
            $query->join('model_has_roles', function($join) use ($customerRoleId) {
                $join->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.role_id', '=', $customerRoleId)
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })->distinct();
        }
        
        // Apply any search filters
        $query->when($request->keywords, 
            fn($q) => $q->where("users.name", "like", "%$request->keywords%"));
        
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {

    	try {
    		$this->validate($request, [
	        'name' => ['required', 'string', 'min:5'],
	        'email' => ['required', 'email', 'unique:users'],
	        'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
	    	]);
    	}
    	catch(\Exception $e) {

    		return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    	}

    	
    	$user =  new User();
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->password = bcrypt($request->password); //Secure
	    $user->save();
	    
	    // Assign Customer role to the new user
	    $user->assignRole('Customer');

        return redirect('/');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
    	
    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }
        
        // Get all purchases for the user if they are a customer
        $purchases = [];
        if ($user->hasRole('Customer')) {
            $purchases = $user->purchases()->with('product')->latest()->get();
        }

        return view('users.profile', compact('user', 'permissions', 'purchases'));
    }

    public function edit(Request $request, User $user = null) {
   
        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }
    
        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }      

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {

        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            Artisan::call('cache:clear');
            
            // Redirect admins back to the users list page
            return redirect()->route('users')->with('success', 'User updated successfully.');
        }

        //$user->syncRoles([1]);
        //Artisan::call('cache:clear');

        // For non-admins or when editing their own profile, redirect to the profile page
        return redirect(route('profile', ['user'=>$user->id]))->with('success', 'Profile updated successfully.');
    }

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
            
            $user->password = bcrypt($request->password); //Secure
            $user->save();
            
            return redirect(route('profile', ['user'=>$user->id]))->with('success', 'Password updated successfully.');
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }
        else {
            $user->password = bcrypt($request->password); //Secure
            $user->save();
            
            return redirect()->route('users')->with('success', 'Password updated successfully.');
        }
    }

    /**
     * Show form for admin to add new user with role selection
     */
    public function addUser(Request $request) {
        // Check if the logged-in user has admin permission
        if(!auth()->user()->hasPermissionTo('admin_users')) {
            abort(401);
        }
        
        // Get all available roles for selection
        $roles = Role::all();
        
        return view('users.add', compact('roles'));
    }
    
    /**
     * Save the new user with selected role
     */
    public function saveUser(Request $request) {
        // Check if the logged-in user has admin permission
        if(!auth()->user()->hasPermissionTo('admin_users')) {
            abort(401);
        }
        
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
                'role' => ['required', 'exists:roles,name'],
            ]);
        } catch(\Exception $e) {
            return redirect()->back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors('Invalid user information.');
        }
        
        // Create new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        
        // Assign role to the new user
        $user->assignRole($request->role);
        
        return redirect()->route('users')->with('success', 'User created successfully.');
    }
} 