<?php

namespace App\Http\Controllers;

use App\Homework;
use App\Teacher;
use App\Student;
use App\GradeSectionSubject;
use App\Topics;
use App\Blocks;
use App\HomeworkBlocks;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HomeworkController extends Controller{

	public function __construct(){

		$this->middleware('oauth', ['except' => []]);

		$this->middleware('authorize:' . __CLASS__, ['except' => [
				"getInitDataHomeworkCreator", 
				"getBlocks", 
				"store", 
				"getTeacherPendingHomework",
				"getStudentPendingHomework",
        "getHomeworkBlocks"
			]
		]);
	}

	public function getInitDataHomeworkCreator(Request $request){

    	$teacherId = $request->user_id;

		$result = [
			"grades" => Teacher::grades($teacherId),
			"topics" => Topics::getAll()
		];

		echo json_encode($result);
  	}

  public function getBlocks(Request $request, $subtopicId){
  		
  		$blocks = Blocks::where("subtopic_id", "=", $subtopicId)->get()->toArray();

  		echo json_encode([ "data" => $blocks ]);

  }

  public function store(Request $request){

  	foreach($request->sections as $section){

  		$this->storeSingleHomework($request, $section);

  	}

  	return response()->json([], 200);

  }

  public function storeSingleHomework($request, $section){

  		$gradeSectionSubjectId = GradeSectionSubject::getIdByGradeAndSection($request->grade_id, $section, $request->subject_id);

  		$homework = Homework::create([
						"grade_section_subject_id" => $gradeSectionSubjectId,
						"topic_id" => $request->topic_id,
						"subtopic_id" => $request->subtopic_id,
						"expiration_date" => $request->expiration_date,
						"created_on" => date("Y-m-d")
					]);


  		Blocks::createHomeworkBlocks($homework->id, $request->blocks);

  }

  public function getTeacherPendingHomework($teacherId){

  		$teacherSections = Teacher::gradesSections($teacherId);

  		$sections = $this->addHomeworkToGradeSection($teacherSections);

  		echo json_encode($sections);

  }

  public function getStudentPendingHomework($studentId){

  	$studentSections = Student::gradesSections($studentId);

		$studentSections = $this->addHomeworkToGradeSection($studentSections);				  		

  	echo json_encode($studentSections);
  }

  public function addHomeworkToGradeSection($studentGradeSectionsSubject){

  		return array_map(function($item){
  			
  			$sectionHomeworks = GradeSectionSubject::getHomeworks($item["grade_section_subject_id"]);

			return  array_merge($item, ["homeworks" => $sectionHomeworks]);

  		},$studentGradeSectionsSubject);
  }

  public function getHomeworkBlocks($homeworkId){

    $blocks = HomeworkBlocks::where("homework_id", "=", $homeworkId)
              ->join("Blocks", "Student_Block.block_id", "=", "Blocks.id")
              ->get()->toArray();

    print_r($blocks);

  }


}
