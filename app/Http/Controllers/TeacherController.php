<?php namespace App\Http\Controllers;

use App\Teacher;

use Illuminate\Http\Request;

class TeacherController extends Controller {

	public function __construct(){
		$this->middleware('oauth', ['except'=>['index', 'show']]);
	}
	
	public function index(){
		$teachers = Teacher::all();
		return $this->createSuccessResponse($teachers, 200);
	}
	public function show($id){
		$teacher = Teacher::find($id);
		if($teacher):
			return $this->createSuccessResponse($teacher, 200);
		endif;

		return $this->createErrorMessage("The teacher with id {$id}, does not exist!", 404);
	}

	public function store(Request $request){
		$this->validateRequest($request);

		$teacher = Teacher::create($request->all());

		return $this->createSuccessResponse("The teacher with id {$teacher->id} has been created", 200);

	}

	public function update(Request $request, $teacher_id){
		$teacher = Teacher::find($teacher_id);

		if($teacher){
			$this->validateRequest($request);

			$teacher->$name = $request->get('name');
			$teacher->$phone = $request->get('phone');
			$teacher->$address = $request->get('address');
			$teacher->$profession = $request->get('profession');

			$teacher->save();

			return $this->createSuccessResponse("The teacher with id {$teacher->id} has been updated.",200);
		}

		return $this->createErrorMessage("The teacher with the specified id does not exists.", 404);
	}
	public function destroy($teacher_id){
		$teacher = Teacher::find($teacher_id);

		if($teacher){
			$teacher->courses()->detach();
			$teacher->delete();

			return $this->createSuccessResponse("The teacher with id {$teacher->id} has been updated.",200);
		}
		return $this->createErrorMessage("The teacher with the specified id does not exists.", 404);
	}
	function validateRequest($request){
		$rules = [
			"name" => "required",
			"phone" => "required|numeric",
			"address" => "required",
			"profession" => "required|in:engineering,math,physics"
		];

		$this->validate($request, $rules);
	}
}