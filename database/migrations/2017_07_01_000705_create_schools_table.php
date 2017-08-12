<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('contact_email');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Grades_Sections');
    }
}
