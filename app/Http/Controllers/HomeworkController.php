<?php

namespace App\Http\Controllers;

use App\Homework;
use App\Teacher;
use App\Student;
use App\GradeSectionSubject;
use App\Topics;
use App\Blocks;
use App\HomeworkBlocks;
use App\StudentBlock;
use App\BaseModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HomeworkController extends Controller{

	public function __construct()
	{

		$this->middleware('oauth', ['except' => []]);

		$this->middleware('authorize:' . __CLASS__, ['except' => [
				"getInitDataHomeworkCreator", 
				"getSubtopicBlocks",
				"getBlock",
				"store", 
				"getTeacherPendingHomework",
				"getStudentPendingHomework",
                "getHomeworkBlocks",
                "homeworkBlockSubmit"
			]
		]);
		
	}

	public function getInitDataHomeworkCreator(Request $request)
	{

    	$teacherId = $request->user_id;

		$result = [
			"grades" => Teacher::grades($teacherId),
			"topics" => Topics::getAll()
		];

		echo json_encode($result);
  	}

  public function getSubtopicBlocks(Request $request, $subtopicId){
  		
  		$blocks = Blocks::where("subtopic_id", "=", $subtopicId)
  		                        ->get()
  		                        ->toArray();

  		echo json_encode([ "data" => $blocks ]);

  }
  
  public function getBlock($studentId, $homeworkBlockId){
        
        $studentBlock = StudentBlock::where("homework_block_id", "=", $homeworkBlockId)
                                ->where("student_id", "=", $studentId)
                                    ->first();
                 
        if (empty($studentBlock)) 
        {
            
            $studentBlock = StudentBlock::create([
        						"homework_block_id" => $homeworkBlockId,
        						"student_id" => $studentId,
        						"created_on" => date("Y-m-d")
    						]);
     
        }
        
        $homework = HomeworkBlocks::find($studentBlock->homework_block_id);
         
        $blockData = DB::table("Homework_Blocks")->where('Homework_Blocks.id', '=', $homeworkBlockId)
              ->leftJoin("Blocks", "Blocks.id", "=", "Homework_Blocks.block_id")
                    ->selectRaw('
                            Homework_Blocks.id AS homework_block_id, 
                            Blocks.id, 
                            Blocks.subtopic_id,
                            Homework_Blocks.total_exercise
                    ')->first();
      
        $blockRoot = "App\Homeworks\Subtopic_".$blockData->subtopic_id."\Block_".$blockData->id;
        $blockRoot = "App\Homeworks\Subtopic_1\Block_1";
        
        $block = new $blockRoot($blockData->total_exercise);
        $excercises = $block->getExcercies();
         
        
        echo json_encode([
                    "block_id" => $blockData->id, 
                    "student_block_id" => $studentBlock->id, 
                    "exercises" => json_encode($excercises)
            ]);
                        
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

  		echo json_encode(["data" => $sections]);

  }

  public function getStudentPendingHomework($studentId){

  	     $studentSections = Student::gradesSections($studentId);

		$studentSections = $this->addHomeworkToGradeSection($studentSections);		
		

  	     echo json_encode(["data" => $studentSections]);
  }

  public function addHomeworkToGradeSection($studentGradeSectionsSubject){

  		return array_map(function($item){
  			
  			$sectionHomeworks = GradeSectionSubject::getHomeworks($item["grade_section_subject_id"]);

			return  array_merge($item, ["homeworks" => $sectionHomeworks]);

  		},$studentGradeSectionsSubject); 
  }

  public function getHomeworkBlocks($homeworkId){

    $blocks = DB::table("Homework_Blocks")->where("homework_id", "=", $homeworkId)
              ->join("Blocks", "Homework_Blocks.block_id", "=", "Blocks.id")
              ->leftJoin("Student_Block", "Homework_Blocks.id", "=", "Student_Block.homework_block_id")
                ->selectRaw('
                        Homework_Blocks.id AS id,
                        Blocks.name,
                        Homework_Blocks.evaluation_mode_id,
                        Homework_Blocks.time_limit,
                        Homework_Blocks.total_exercise,
                        IF(punctuation,1,0) AS completed
                    ')
                ->get()->toArray();
                
    echo json_encode(["data" => $blocks]);

  }
  
  public function homeworkBlockSubmit(Request $request, $studentBlockId){
      
        $blockJson = json_decode($request->block_json); 
      
        $completitionTime = BaseModel::getMinutesBetweenTwoDates($request->start_date, $request->end_date);
        
        $blockData = DB::table('Student_Block')
                            ->join('Homework_Blocks', 'Student_Block.homework_block_id', '=', 'Homework_Blocks.id')
                                ->join('Blocks', 'Homework_Blocks.block_id', '=', 'Blocks.id')
                                    ->selectRaw('
                                            Blocks.id,
                                            Blocks.subtopic_id
                                        ')
                                        ->where("Student_Block.id", "=", $studentBlockId)
                                            ->first();
                                    
        $blockRoot = "App\Homeworks\Subtopic_".$blockData->subtopic_id."\Block_".$blockData->id;
        $block = new $blockRoot();
        $blockResults = $block->getBlockResult($blockJson->data);
        
        StudentBlock::where('id', $studentBlockId)
                ->update([
                    'block_json' => $request->block_json,
                    'completion_time'=> $completitionTime,
                    'total_correct_answers' => $blockResults["totalCorrectAnswers"],
                    'total_incocorrect_answers' => $blockResults["totalIncorrectAnswers"],
                    'punctuation' => $blockResults["punctuation"],
                    'completion_date' => date('Y-m-d',strtotime($request->start_date))
                ]);
                
        return response()->json([], 200);
        
        
  }
  
}
