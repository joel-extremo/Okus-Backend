<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentBlock extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Student_Block';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
    	           'student_id', 
    	           'homework_block_id', 
    	           'block_json', 
    	           'completion_time', 
    	           'total_correct_answers', 
    	           'total_incocorrect_answers', 
    	           'completion_date', 
    	           'punctuation'
               ];

    public $timestamps = false;
}
