<?php

use Illuminate\Database\Seeder;

class SubjectsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::table('Subjects')->truncate();

        DB::table('Subjects')->insert([

            [
                'id' => 1,
                'subject' => 'Matematica',  
            ],[
                'id' => 2,
                'subject' => 'Lenguaje',  
            ],[
                'id' => 3,
                'subject' => 'Sociales',  
            ]
        ]);

    }
}
