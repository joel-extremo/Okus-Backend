<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('status_id')->unsigned();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email');
            $table->string('password');
            $table->nullableTimestamps();

            $table->foreign('status_id')->references('id')->on('Users_Status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Teachers');
    }
}
