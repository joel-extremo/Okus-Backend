<?php

use Illuminate\Database\Seeder;

class UserStatusTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::table('Users_Status')->truncate();
        

        DB::table('Users_Status')->insert([

            [
                'id' => 1,
                'status' => 'Confirm email pending',  
            ],[
                'id' => 2,
                'status' => 'Active',  
            ],[
                'id' => 3,
                'status' => 'Inactive',  
            ]
        ]);

    }
}
