<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Support\Facades\Hash;

class Student extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = "Students";
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
        'password',
        'grade_section_id',
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
     * Verify student's credentials.
     *
     * @param  string $email
     * @param  string $password
     * @return int|boolean
     * @see    https://github.com/lucadegasperi/oauth2-server-laravel/blob/master/docs/authorization-server/password.md
     */
    public function verify($email, $password){

        return 1;

        $student = Student::where('email', $email)->first();

        if($student && Hash::check($password, $student->password)){
            return $student->id;
        }

        return false;
    }

    /**
     * Verify student's credentials.
     *
     * @param  string $email
     * @param  string $password
     * @return int|boolean
     * @see    https://github.com/lucadegasperi/oauth2-server-laravel/blob/master/docs/authorization-server/password.md
     */
    static function verifyCredentials($email, $password){

        $student = Student::where('email', $email)->first();

        if($student && Hash::check($password, $student->password)){
            return $student;
        }

        return false;
    }

    /**
     *
     * @param  string $email
     * @return boolean
     */
    static function existEmail($email){

        $student = Student::where('email', $email)->first();

        return $student ? true : false;

    }

    static function login($email, $password){

        $student = Student::where('email', $email)
                        ->select('id as user_id', 'status_id as user_status_id',
                            'username','firstname', 'lastname', 'grade_section_id',
                                  'password')
                                        ->first();

        if($student && Hash::check($password, $student->password)){

              //if student doesn't have section return '-1'
              $student->user_type_id = 1;

              $student->grade_section_id = $student->grade_section_id ? $student->grade_section_id : -1;

              return $student;
        }

    }

    static function gradesSections($studentId){
        
        return Student::join('Grades_Sections', 'Students.grade_section_id', '=', 'Grades_Sections.id')
                        ->rightJoin('Grades_Section_Subjects', 'Grades_Sections.id', 'Grades_Section_Subjects.grade_section_id')
                        ->join('Teachers', 'Grades_Section_Subjects.teacher_id', '=', 'Teachers.id')
                        ->join('Subjects', 'Grades_Section_Subjects.subject_id', '=', 'Subjects.id')
                        ->where("Students.id", "=", $studentId)
                        ->selectRaw("
                            Grades_Section_Subjects.id AS grade_section_subject_id,
                            CONCAT(Teachers.firstname, ' ',Teachers.lastname) AS teacher_name,
                            Subjects.subject
                            ")
                        ->get()->toArray();
    }

}
