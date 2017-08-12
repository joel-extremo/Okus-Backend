<?php

use Illuminate\Database\Seeder;

class GradesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Grades')->truncate();

        DB::table('Grades')->insert([

            [
                'id' => 1,
                'grade' => '1er de Basica',  
            ],[
                'id' => 2,
                'grade' => '2do de Basica',  
            ],[
                'id' => 3,
                'grade' => '3er de Basica',  
            ],[
                'id' => 4,
                'grade' => '4to de Basica',  
            ],[
                'id' => 5,
                'grade' => '5to de Basica',  
            ],[
                'id' => 6,
                'grade' => '6to de Basica',  
            ],[
                'id' => 7,
                'grade' => '7mo de Basica',  
            ],[
                'id' => 8,
                'grade' => '8vo de Basica',  
            ],[
                'id' => 9,
                'grade' => '1er de Media',  
            ],[
                'id' => 10,
                'grade' => '2do de Media',  
            ],[
                'id' => 11,
                'grade' => '3er de Media',  
            ],[
                'id' => 12,
                'grade' => '4to de Media',  
            ],
        ]);

    }
}
