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

class UsersController extends Controller
{

    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web')->except(['register', 'doRegister', 'login', 'doLogin']);
    }

    public function list(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('show_users'))
            abort(401);
        $query = User::select('*');
        $query->when(
            $request->keywords,
            fn($q) => $q->where("name", "like", "%$request->keywords%")
        );
        $users = $query->get();
        return view('users.list', compact('users'));
    }

    public function register(Request $request)
    {
        return view('users.register');
    }

    public function doRegister(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->credit = 0.00;
        $user->created_by_admin = false; // Public registration
        $user->save();

        $user->assignRole('Customer');

        return redirect('/');
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request)
    {

        Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null)
    {

        $user = $user ?? auth()->user();
        if (auth()->id() != $user->id) {
            if (!auth()->user()->hasPermissionTo('show_users'))
                abort(401);
        }

        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null)
    {
        if (!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }
        $user = $user ?? new User();
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user = null)
    {
        if (!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users,email,' . ($user->id ?? 'NULL') . ',id'],
        ]);

        $user = $user ?? new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->credit = $user->credit ?? 0.00;
        $user->created_by_admin = $user->exists ? $user->created_by_admin : true; // Set to true for new users created by admin
        $user->save();

        if (auth()->user()->hasPermissionTo('admin_users')) {
            $roles = $request->roles ?? [];
            // Prevent assigning "Employee" role to users who registered publicly
            if (!$user->created_by_admin && in_array('Employee', $roles)) {
                return redirect()->back()->withErrors('Cannot assign the Employee role to a user who registered publicly.');
            }
            $user->syncRoles($roles);
            $user->syncPermissions($request->permissions ?? []);
        }

        return redirect()->route('users');
    }

    public function delete(Request $request, User $user)
    {

        if (!auth()->user()->hasPermissionTo('delete_users'))
            abort(401);

        //$user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null)
    {

        $user = $user ?? auth()->user();
        if (auth()->id() != $user?->id) {
            if (!auth()->user()->hasPermissionTo('edit_users'))
                abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user)
    {

        if (auth()->id() == $user?->id) {

            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {

                Auth::logout();
                return redirect('/');
            }
        } else if (!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user' => $user->id]));
    }
    public function purchases(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('Customer')) {
            abort(403, 'Only customers can view their purchases.');
        }

        $purchases = $user->purchases()->with('product')->get();

        return view('users.purchases', compact('purchases'));
    }

    public function addCredit(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('Employee')) {
            abort(403, 'Only employees can add credit.');
        }

        return view('users.add_credit', compact('user'));
    }

    public function saveCredit(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('Employee')) {
            abort(403, 'Only employees can add credit.');
        }

        $this->validate($request, [
            'credit' => ['required', 'numeric', 'min:0'],
        ]);

        $user->credit += $request->credit;
        $user->save();

        return redirect()->route('users')->with('success', 'Credit added successfully!');
    }
}