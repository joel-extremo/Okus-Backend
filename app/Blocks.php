<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blocks extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Blocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['id', 'name', 'subtopic_id', 'json_default_settings'];

    static function createHomeworkBlocks($homeworkId, $blocks){

        foreach($blocks as $block){

            $block = (object)$block;

            HomeworkBlocks::create([
                "homework_id" => $homeworkId,
                "block_id" => $block->id,
                "evaluation_mode_id" => $block->evaluation_mode_id,
                "time_limit" => $block->time_limit,
                "total_exercise" => $block->total_exercise
            ]);
        }

    }

}
