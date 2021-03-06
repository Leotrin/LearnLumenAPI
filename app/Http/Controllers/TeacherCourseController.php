<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\Course;

class TeacherCourseController extends Controller {

	public function __construct(){
		$this->middleware('oauth', ['except'=>['index']]);
	}

	public function index($teacher_id){
		$teacher = Teacher::find($teacher_id);

		if($teacher){
			$courses = $teacher->courses;
			return $this->createSuccessResponse($courses, 200);
		}

		return $this->createErrorMessage("The teacher with specific id does not exist.", 404);
	}
	public function store(Request $request, $teacher_id){
		$teacher = Teacher::find($teacher_id);
		if($teacher){
			$this->validateRequest($request);
			$course = Course::create([
				"title" => $request->get('title'),
				"description" => $request->get('description'),
				"value" => $request->get('value'),
				"teacher_id" => $teacher->id
			]);

			return $this->createSuccessResponse("The course with id {$course->id} has been created and associated with the teacher with id {$teacher->id}", 201);
		}
		return $this->createErrorMessage("The teacher with id{$teacher_id} does not exists.", 404);
	}
	public function update(Request $requests, $teacher_id, $course_id){
		$teacher = Teacher::find($teacher_id);

		if($teacher):
			$course = Course::find($course_id);
			if($course):
				$this->validateRequest($request);
				$course->title = $request->get('title');
				$course->description = $request->get('descripition');
				$course->value = $request->get('value');
				$course->teacher_id = $teacher_id;

				$course->save();

				return $this->createSuccessResponse("The course with id {$course_id} has been updated.",200);
			endif;
			return $this->createErrorMessage("Does not exists a teacher with the id {teacher_id}", 404);
		endif;
		return $this->createErrorMessage("Does not exists a teacher with the id {teacher_id}", 404);
	}
	public function destroy($teacher_id, $course_id){
		$teacher = Teacher::find($teacher_id);
		if($teacher):
			$course = Course::find($course_id);
			if($course){
				$course->delete();
				return $this->createSuccessResponse("The course with id {$course->id} has been deleted.",200);
			}
			return $this->createErrorMessage("The course with the specified id does not exists.", 404);
		endif;
		return $this->createErrorMessage("The teacher with id {$teacher_id} does not exists.", 404);
	}
	function validateRequest($request){
		$rules = [
			"title" => "required",
			"description" => "required",
			"value" => "required|numeric"
		];

		$this->validate($request, $rules);
	}
}