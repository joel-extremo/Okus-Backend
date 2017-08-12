<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getHomeworkStudent(){
      $homework = DB::table('Test_Homework')
          ->where('completed', '=', 0)
          ->get();

          return response()->json($homework, 200);

    }

    public function getHomeworkTeacher(){
      $homework = DB::table('Test_Homework')
      ->selectRaw("
      Test_Homework.*,
      Date_Format(Test_Homework.date,'%d/%m/%Y') as date,
          if(homework_type = 1, 'Descomponer numeros', 'Posicion de cifras') as homework_name")
          ->where('completed', '=', 0)
          ->orderBy('id', 'desc')
          ->get();

          return response()->json(["data" => $homework], 200);

    }

    public function createHomework(Request $request){

          $query = "INSERT INTO Test_Homework(homework_type, total_exercise, completed, date)
          VALUES (".$request->homework_type.",".$request->total_exercise.",0,'".$request->date."')";

          DB::select($query);

          return response()->json([], 200);

    }

    public function getHomework($id){

      $homework = DB::table('Test_Homework')
          ->where('id', '=', $id)
          ->first();

          if($homework->homework_type == 1){
            $data = $this->exercise1($homework->total_exercise);
          }else{
            $data = $this->exercise2($homework->total_exercise);
          }


          return response()->json($data, 200);

    }

    public function exercise2($totalExercies){

           $data = [];

           $range = [
               "min" => 0,
               "max" => 1000000
           ];

           for ($i=0; $i < $totalExercies; $i++) {

               $number = rand(0,9);

               $number2 = (string)rand($range["min"],$range["max"]);

               $position = rand(0, (strlen($range["max"])-1));

               $number2 = str_replace($number, '', $number2);

               $number2[$position] = $number;

               $data[] = [
                   "numberToFind" => $number,
                   "completeNumber" => $number2,
                   "result" => $this->getPosition($number, $number2)
               ];
            }



          return $data;
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

    public function exercise1($totalExercies){

      $data = [];

      for ($i=0; $i < $totalExercies; $i++) {

          $number = rand(1000,1000000);

          $arrayNumbers = array_reverse(str_split($number));

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

          $result = [];

          foreach($units as $index => $unit){
              $result[$unit] = isset($arrayNumbers[$index]) ? $arrayNumbers[$index] : "";
          }

          $data[] = [
              "number" => number_format($number),
              "units" => $result
          ];

      }

      return $data;

    }
}
