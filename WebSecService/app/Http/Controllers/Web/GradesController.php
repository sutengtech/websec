<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Grade;
use App\Models\User;

class GradesController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
    }

	public function list(Request $request) {

		// Check if user has permission to view grades
		$user = Auth::user();
		
		if (!$user->hasPermissionTo('show_exgrades') && !$user->hasPermissionTo('view_own_exgrades')) {
			abort(403, 'You do not have permission to view student grades');
		}

		$query = Grade::select("grades.*");
		$query->join('users', 'users.id', 'grades.user_id');
		$query->join('courses', 'courses.id', 'grades.course_id');

		// If user only has permission to view own grades (exstudent), restrict to their grades only
		if (!$user->hasPermissionTo('show_exgrades') && $user->hasPermissionTo('view_own_exgrades')) {
			$query->where('grades.user_id', $user->id);
		}

		$query->when($request->keywords, 
		fn($q)=> $q->where(function($subQuery) use($request){
			$subQuery->orWhere("users.name", "like", "%$request->keywords%");
			$subQuery->orWhere("courses.name", "like", "%$request->keywords%");
		}));

		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$grades = $query->get();

		return view('grades.list', compact('grades'));
	}

	public function edit(Request $request, Grade $grade = null) {

		// Check if user has permission to edit grades
		if(!Auth::user()->hasPermissionTo('edit_exgrades')) {
			abort(403, 'You do not have permission to edit student grades');
		}

		$grade = $grade??new Grade();

		$users = User::select('id', 'name')->get();
		$courses = Course::select('id', 'name')->get();

		return view('grades.edit', compact('grade', 'courses', 'users'));
	}

	public function save(Request $request, Grade $grade = null) {

		// Check if user has permission to edit grades
		if(!Auth::user()->hasPermissionTo('edit_exgrades')) {
			abort(403, 'You do not have permission to edit student grades');
		}

		$this->validate($request, [
	        'user_id' => ['required', 'numeric', 'exists:users,id'],
	        'course_id' => ['required', 'numeric', 'exists:courses,id'],
	        'degree' => ['required', 'numeric', 'max:100']
	    ]);

		$grade = $grade??new Grade();
		$grade->fill($request->all());
		$grade->save();

		return redirect()->route('grades_list');
	}

	public function freeze(Request $request, Grade $grade) {

		// Check if user has permission to edit grades (freeze is a form of editing)
		if(!Auth::user()->hasPermissionTo('edit_exgrades')) {
			abort(403, 'You do not have permission to modify student grades');
		}

		$grade->freezed = 1;
		$grade->save();

		return redirect()->route('grades_list');
	}

	public function unfreeze(Request $request, Grade $grade) {

		// Check if user has permission to edit grades (unfreeze is a form of editing)
		if(!Auth::user()->hasPermissionTo('edit_exgrades')) {
			abort(403, 'You do not have permission to modify student grades');
		}

		$grade->freezed = 0;
		$grade->save();

		return redirect()->route('grades_list');
	}

	public function delete(Request $request, Grade $grade) {

		// Check if user has permission to delete grades
		if(!Auth::user()->hasPermissionTo('delete_exgrades')) {
			abort(403, 'You do not have permission to delete student grades');
		}

		$grade->delete();

		return redirect()->route('grades_list');
	}

	public function submitAppeal(Request $request, Grade $grade) {
		// Check if user has permission to submit appeals
		$user = Auth::user();
		
		if (!$user->hasPermissionTo('submit_grade_appeal')) {
			abort(403, 'You do not have permission to submit appeals');
		}

		// Check if the grade belongs to the authenticated user
		if ($grade->user_id !== $user->id) {
			abort(403, 'You can only appeal your own grades');
		}

		// Check if the grade is already appealed
		if ($grade->appeal_status !== 'none') {
			return redirect()->route('grades_list')->with('error', 'This grade has already been appealed');
		}

		$this->validate($request, [
			'appeal_reason' => ['required', 'string', 'max:1000']
		]);

		$grade->update([
			'appeal_status' => 'pending',
			'appeal_reason' => $request->appeal_reason,
			'appealed_at' => now()
		]);

		return redirect()->route('grades_list')->with('success', 'Appeal submitted successfully');
	}

	public function respondToAppeal(Request $request, Grade $grade) {
		// Check if user has permission to respond to appeals
		$user = Auth::user();
		
		if (!$user->hasPermissionTo('respond_to_grade_appeal')) {
			abort(403, 'You do not have permission to respond to appeals');
		}

		// Check if there's a pending appeal
		if ($grade->appeal_status !== 'pending') {
			return redirect()->route('grades_list')->with('error', 'No pending appeal for this grade');
		}

		$this->validate($request, [
			'appeal_decision' => ['required', 'in:approved,rejected'],
			'appeal_response' => ['required', 'string', 'max:1000']
		]);

		$grade->update([
			'appeal_status' => $request->appeal_decision,
			'appeal_response' => $request->appeal_response,
			'appeal_responded_at' => now()
		]);

		return redirect()->route('grades_list')->with('success', 'Appeal response submitted successfully');
	}
}