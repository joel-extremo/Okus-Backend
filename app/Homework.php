<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Homework extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Homeworks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['grade_section_subject_id', 'topic_id', 'subtopic_id', 'expiration_date', "created_on"];

    public $timestamps = false;
}
