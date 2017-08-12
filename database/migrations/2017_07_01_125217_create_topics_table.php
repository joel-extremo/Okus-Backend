<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('subject_id')->unsigned();
            $table->integer('super_topic_id')->unsigned();
            
            $table->foreign('subject_id')->references('id')->on('Subjects');
            $table->foreign('super_topic_id')->references('id')->on('Topics')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Topics');
    }
}
