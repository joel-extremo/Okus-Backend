<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeSectionsSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Grades_Section_Subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grade_section_id')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->integer('subject_id')->unsigned();

            $table->foreign('grade_section_id')->references('id')->on('Grades_Sections');
            $table->foreign('teacher_id')->references('id')->on('Teachers');
            $table->foreign('subject_id')->references('id')->on('Subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Grades_Section_Subjects');
    }
}
