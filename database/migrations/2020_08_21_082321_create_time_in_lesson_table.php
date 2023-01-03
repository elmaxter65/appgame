<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeInLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_in_lesson', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->bigInteger('lesson_id')->unsigned()->index()->nullable();
            $table->integer('time_spent_sec');
            $table->timestamps();
        });
        Schema::table('time_in_lesson', function($table) {
            $table->foreign('user_id')->references('id')->on('user_apps');
            $table->foreign('lesson_id')->references('id')->on('lessons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_in_lesson');
    }
}
