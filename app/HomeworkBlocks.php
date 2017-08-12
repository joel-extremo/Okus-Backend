<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HomeworkBlocks extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Homework_Blocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['homework_id', 'block_id', 'evaluation_mode_id', 'time_limit', "total_exercise"];

    public $timestamps = false;
}
