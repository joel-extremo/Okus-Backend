<?php

namespace App;

use App\GradeSectionSubject;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

class Teacher extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Teachers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 
        'status_id', 
        'username', 
        'firstname',
        'lastname',
        'email',
        'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function gradeSectionSubject()
    {
        return $this->hasMany('App\GradeSectionSubject');
    }

    static function login($email, $password){

        $teacher = DB::table('Teachers')
            ->leftJoin('Grades_Section_Subjects', 'Teachers.id', '=', 'Grades_Section_Subjects.teacher_id')
            ->selectRaw('
                Teachers.id as user_id,
                Teachers.status_id as user_status_id,
                Teachers.username,
                Teachers.firstname,
                Teachers.lastname,
                Teachers.password,
                ifnull(count(Grades_Section_Subjects.id), 0)as total_grade_section_subject')
            ->where('Teachers.email', $email)
            ->groupBy('Teachers.id')
            ->first();

        if($teacher && Hash::check($password, $teacher->password)){

            unset($teacher->password);

            return $teacher;
        
        }

    }

    static function loginWithoutPassword($email){

        $teacher = DB::table('Teachers')
            ->leftJoin('Grades_Section_Subjects', 'Teachers.id', '=', 'Grades_Section_Subjects.teacher_id')
            ->selectRaw('
                Teachers.id as user_id,
                Teachers.status_id as user_status_id,
                Teachers.username,
                Teachers.firstname,
                Teachers.lastname,
                ifnull(count(Grades_Section_Subjects.id), 0)as total_grade_section_subject')
            ->where('Teachers.email', $email)
            ->groupBy('Teachers.id')
            ->first();

        if($teacher){

            return $teacher;
        
        }

    }

    static function grades($teacherId){

        $gradeSections = GradeSectionSubject::getTeacherGradesSections($teacherId);

        $grades = array_reduce($gradeSections, function($prev, $actual){

            if(array_search($actual->grade, array_column($prev, 'name')) === False) {
                array_push($prev, ["id" => $actual->id, "name" => $actual->grade]);
            }

            return $prev;
        }, []);

        $grades = array_map(function($item) use ($gradeSections){

                $a = array_filter($gradeSections, function($item2) use($item){
                    return $item2->grade == $item["name"];
                });

                $a = array_values($a);

                $a = array_map(function($item2){
                    return $item2->section;
                }, $a);

                return array_merge($item, ["sections" => $a]);
        },$grades);


        $grades = array_map(function($item) use ($gradeSections){
            return array_merge($item, ["sections" => array_unique($item["sections"])]);  
        },$grades);

        return $grades;

    }

    static function gradesSections($teacherId){

        return GradeSectionSubject::where("teacher_id", "=", $teacherId)
                    ->leftJoin('Grades_Sections', 'Grades_Section_Subjects.grade_section_id', '=', 'Grades_Sections.id')
                    ->leftJoin('Grades', 'Grades_Sections.grade_id', '=', 'Grades.id')
                    ->leftJoin('Subjects', 'Grades_Section_Subjects.subject_id', '=', 'Subjects.id')
                    ->selectRaw("
                        Grades.grade,
                        Grades_Sections.section,
                        Grades_Section_Subjects.id AS grade_section_subject_id,
                        Subjects.subject
                        ")
                    ->get()->toArray();

    }

}
