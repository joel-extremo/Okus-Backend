<?php 

namespace App\Http\Controllers;

use App\Teacher;
use App\GradeSection;
use App\GradeSectionSubject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller{

	public function __construct(){

		$this->middleware('oauth', ['except' => ["store"]]);
		
		$this->middleware('authorize:' . __CLASS__, ['except' => ["store"]]);
	}

	/**
     * Store teacher in DB
     *
     * @param array $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function store(Request $request){

		$this->validateRequest_store($request);

		//set student data good to save
		$teacherData = [
					'email' => $request->email,
					'password' => $request->password ? Hash::make($request->password) : "",
					'status_id' => 1,
					'code'=> Hash::make($request->email)
				];

		//create student
		$teacherId = Teacher::create($teacherData)->id;

		$gradeSectionSubjects = json_decode($request->grade_section_subject);

		// print_r($a);
		// die();

		foreach($gradeSectionSubjects AS $item){

			$gradeSectionId = GradeSection::getByCode($item->grade_section_code)->id;

			$gradeSectionSubject = GradeSectionSubject::where("grade_section_id", "=", $gradeSectionId)
						->where("subject_id", "=", $item->subject_id)->first();

			if(!$gradeSectionSubject){
				GradeSectionSubject::create([
					"grade_section_id" => $gradeSectionId,
					"teacher_id" => $teacherId,
					"subject_id" => $item->subject_id
					]);
			}
		}

		$gradeSections = GradeSection::getByTeacherId($teacherId);

		$schoolGradeSectionNames = [];

		foreach($gradeSections as $item){
			$schoolGradeSectionNames[] = "(".$item->subject.") ".$item->grade." seccion ".$item->section." del ".$item->school_name;
		}

		$result = [
			"access_token" => app("oauth2-server.authorizer")->issueAccessToken()["access_token"],
			"user_data" => [
				"user_id" => $teacherId,
				"user_status_id" => 1,
				"user_type_id" => 2,
				"total_groups" => count($schoolGradeSectionNames),
				"groups_string" => $schoolGradeSectionNames
			]
		];

		return response()->json($result, 200);
	}

	public function validateRequest_store(Request $request){

		$rules = [
			'email' => 'required|email|unique:Teachers', 
		];

		$this->validate($request, $rules);
	}

	
}