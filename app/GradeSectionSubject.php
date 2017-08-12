<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GradeSectionSubject extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Grades_Section_Subjects';

    public $timestamps = false;
    /**
     * Get the teacher that owns the grade section subject.
     */
    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	   protected $fillable = ['grade_section_id', 'teacher_id', 'subject_id'];

     static function getTeacherGradesSections($teacherId){

       return $gradeSections = DB::table('Grades_Section_Subjects')
           ->leftJoin('Grades_Sections', 'Grades_Section_Subjects.grade_section_id', '=', 'Grades_Sections.id')
           ->leftJoin('Grades', 'Grades_Sections.grade_id', '=', 'Grades.id')
           ->selectRaw('*')
           ->where('Grades_Section_Subjects.teacher_id', $teacherId)
           ->get()->toArray();

     }

     static function getIdByGradeAndSection($gradeId, $section, $subjectId){

        $result = DB::table('Grades_Sections as GS')
            ->join('Grades_Section_Subjects as GSS', 'GS.id', '=', 'GSS.grade_section_id')
            ->selectRaw('GSS.id AS grade_section_subject_id')
            ->where('GS.grade_id', "=",$gradeId)
            ->where('GS.section', "=",$section)
            ->where('GSS.subject_id', "=",$subjectId)
            ->get()->first();

        return $result->grade_section_subject_id;
    }

    static function getHomeworks($gradeSectionSubjectId){

        return Homework::leftJoin('Topics', 'Homeworks.topic_id', '=', 'Topics.id')
                ->leftJoin('Subtopics', 'Homeworks.subtopic_id', '=', 'Subtopics.id')
                ->where("grade_section_subject_id", "=", $gradeSectionSubjectId)
                ->selectRaw("
                        Homeworks.id,
                        Subtopics.name AS subtopic_name,
                        Topics.name AS topic_name,
                        Homeworks.expiration_date
                    ")
                ->where("Homeworks.expiration_date", ">", date("Y-m-d 23:59:59"))
                ->get()->toArray();

    }

}
