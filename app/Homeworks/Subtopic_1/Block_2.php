<?php

namespace App\Homeworks\Subtopic_1;

class Block_2{
    
    protected $totalExecise;
    
    public function __construct($totalExecise = null){
        
        $this->totalExecise = $totalExecise;
    
    }
    
    public function getExcercies(){
        
        echo "sd"
        
    }
    
    public function getPosition($number, $number2){

        $units = [
            "unidad",
            "decena",
            "centena",
            "unidadDeMillar",
            "decenaDeMillar",
            "centenaDeMillar",
            "unidadDeMillon",
            "decenaDeMillon",
            "centenaDeMillon"
        ];

        //invert the number
        $temp = strrev($number2);

        $index = strpos($temp, ((string)$number));

        return $units[$index];

    }
    
    public function getBlockResult($exercises){
        
        $result = [
            "totalCorrectAnswers" => 0,
            "totalIncorrectAnswers" => 0,
            "punctuation" => 0
        ];
        
        $totalCorrectAnswers = 0;
        $totalIncorrectAnswers = 0;
        $punctuation = 0;
        /*
        print_r($exercises);
        die();
        */
        foreach($exercises AS $exercise){
            
            if($exercise->place == $exercise->result){
                $result["totalCorrectAnswers"]++;
            }else{
                $result["totalIncorrectAnswers"]++;
            }
        }
        
        $result["punctuation"] = ($result["totalCorrectAnswers"]/count($exercises))*100;
        
        return $result;
        

    }
    
}

class t2{
    
}


