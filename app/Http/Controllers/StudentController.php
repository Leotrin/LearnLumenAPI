<?php namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;


class StudentController extends Controller {

	public function __construct(){
		$this->middleware('oauth', ['except'=>['index', 'show']]);
	}

	public function index(){
		$students = Student::all();
		return $this->createSuccessResponse($students, 200);
	}
	public function show($id){
		$student = Student::find($id);
		if($student):
			return $this->createSuccessResponse($student, 200);
		endif;

		return $this->createErrorMessage("The student with id {$id}, does not exist!", 404);
	}
	public function update(Request $request, $student_id){
		$student = Student::find($student_id);

		if($student){
			$this->validateRequest($request);

			$student->name = $request->get('name');
			$student->phone = $request->get('phone');
			$student->address = $request->get('address');
			$student->career = $request->get('career');

			$student->save();

			return $this->createSuccessResponse("The student with id {$student->id} has been updated.",200);
		}

		return $this->createErrorMessage("The student with the specified id does not exists.", 404);
	}
	public function store(Request $request){
		$this->validateRequest($request);

		$student = Student::create($request->all());

		return $this->createSuccessResponse("The student with id {$student->id} has been created", 200);

	}
	public function destroy($student_id){
		$student = Student::find($student_id);

		if($student){
			$student->courses()->detach();
			$student->delete();

			return $this->createSuccessResponse("The student with id {$student->id} has been deleted.",200);
		}
		return $this->createErrorMessage("The student with the specified id does not exists.", 404);
	}
	function validateRequest($request){
		$rules = [
			"name" => "required",
			"phone" => "required|numeric",
			"address" => "required",
			"career" => "required|in:engineering,math,physics"
		];

		$this->validate($request, $rules);
	}
}