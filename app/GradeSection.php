<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GradeSection extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Grades_Sections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['code', 'grade_id', 'section', 'school_id'];

    static function getByCode($code){

        //verify if code was sent
        if($code){

            $gradeSection = DB::table('Grades_Sections')
            ->leftJoin('Grades', 'Grades_Sections.grade_id', '=', 'Grades.id')
            ->selectRaw('
                Grades_Sections.id,
                Grades_Sections.code,
                Grades_Sections.section,
                Grades.grade')
            ->where('Grades_Sections.code', $code)
            ->first();

            //verify if exist a grade section with that code
            if(!$gradeSection){
                response()->json(["group code error"] , 404)->send();
                die();
            }else{
                return $gradeSection;
            }

        }else{
            return null;
        }
    }

    static function getByTeacherId($teacherId){

        return $gradeSection = DB::table('Grades_Section_Subjects as GSS')
        ->leftJoin('Subjects as S', 'GSS.subject_id', '=', 'S.id')
        ->leftJoin('Grades_Sections as GS', 'GSS.grade_section_id', '=', 'GS.id')
        ->leftJoin('Schools as SC', 'GS.school_id', '=', 'SC.id')
        ->leftJoin('Grades as G', 'GS.grade_id', '=', 'G.id')
        ->selectRaw('
            S.subject,
            SC.name as school_name,
            GS.section,
            G.grade
            ')
        ->where('GSS.teacher_id', $teacherId)
        ->get();

    }

}