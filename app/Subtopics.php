<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subtopics extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Subtopics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['id', 'name', 'topic_id'];

}
