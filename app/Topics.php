<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Topics extends Model{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Topics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['id', 'name', 'subject_id'];

    static function getTopicsAndSubtopics(){

        return $gradeSection = DB::table('Subtopics')
        ->leftJoin('Topics', 'Subtopics.topic_id', '=', 'Topics.id')
        ->selectRaw('
            Topics.name as topic_name,
            Subtopics.name as subtopic_name,
            Subtopics.topic_id,
            Subtopics.id as subtopic_id
            ')
        ->get()->toArray();

    }

    static function getAll(){

        $a = Topics::getTopicsAndSubtopics();
 
        $c = array_reduce($a, function($prev, $actual){

            if(array_search($actual->topic_name, array_column($prev, 'name')) === False) {
                array_push($prev, ["id" => $actual->topic_id, "name" => $actual->topic_name]);
            }

            return $prev;
        }, []);


        $l = array_map(function($item) use ($a){

                $k = array_filter($a, function($item2) use($item){
                    return $item2->topic_name == $item["name"];
                });

                $k = array_values($k);

                $k = array_map(function($item2){
                    return ["id" => $item2->subtopic_id, "name" => $item2->subtopic_name];
                }, $k);

                return array_merge($item, ["subtopics" => $k]);
        },$c);

        return $l;

    }

}
