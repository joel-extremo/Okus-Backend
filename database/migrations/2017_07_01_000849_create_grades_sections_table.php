<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Grades_Sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('grade_id')->unsigned();
            $table->string('section');
            $table->integer('school_id')->unsigned();

            $table->foreign('school_id')->references('id')->on('Schools');
            $table->foreign('grade_id')->references('id')->on('Grades');
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
