<?php 

namespace App\Http\Controllers;

use App\Student;
use App\GradeSection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller{

	public function __construct(){

		$this->middleware('oauth', ['except' => ['index', 'store']]);
		
		$this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'store', 'show', 'update', 'destroy']]);
	}

	public function index(){

		$students = Student::all();
		return $this->success($students, 200);
	}


	/**
     * Store student in DB
     *
     * @param array $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function store(Request $request){

		//validate incomming data
		$this->validate($request, ['email' => 'required|email|unique:Students']);

		$gradeSection = GradeSection::getByCode($request->get('grade_section_code'));

		//set student data good to save
		$studentData = [
					'email' => $request->get('email'),
					'password' => $request->get('password') ? Hash::make($request->get('password')) : "",
					'grade_section_id' => $gradeSection->id,
					'status_id' => 1,
					'code'=> Hash::make($request->get('email'))
				];

		//create student
		$studentID = Student::create($studentData)->id;

		//Sanitize data to send to the frontend
 		$studentData = array_merge($studentData, [
	 			"user_id" => $studentID, 
	 			"user_type_id" => 1, 
	 			"user_status_id" => $studentData["status_id"],
	 			"grade_name" => $gradeSection->grade,
	 			"grade_section" => $gradeSection->section
 			]);
 		$studentData = array_diff_key($studentData, array_flip(["password", "status_id", "code"]));

		$result = [
				"access_token" => app("oauth2-server.authorizer")->issueAccessToken()["access_token"],
				"user_data" => $studentData
			];

		return response()->json($result, 200);

	}

	public function show($id){

		$student = Student::find($id);

		if(!$student){
			return $this->error("The student with {$id} doesn't exist", 404);
		}

		return $this->success($student, 200);
	}

	public function update(Request $request, $id){

		$student = Student::find($id);

		if(!$student){
			return $this->error("The student with {$id} doesn't exist", 404);
		}

		$this->updateValidateRequest($request);

		$student->name 		= $request->get('name');
		$student->password 	= Hash::make($request->get('password'));

		$student->save();

		return $this->success("The user with id {$student->id} has been updated", 200);
	}

	public function destroy($id){

		$student = Student::find($id);

		if(!$student){
			return $this->error("The student with {$id} doesn't exist", 404);
		}

		$student->delete();

		return $this->success("The user with with id {$id} has been deleted", 200);
	}

	public function updateValidateRequest(Request $request){

		$rules = [
			'name' => 'min:2', 
			'password' => 'min:6'
		];

		$this->validate($request, $rules);
	}

	public function isAuthorized(Request $request){

		$resource = "users";
		// $user     = User::find($this->getArgs($request)["user_id"]);

		return $this->authorizeUser($request, $resource);
	}
}