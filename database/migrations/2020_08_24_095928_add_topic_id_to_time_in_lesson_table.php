<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTopicIdToTimeInLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_in_lesson', function (Blueprint $table) {
            $table->bigInteger('topic_id')->unsigned()->index()->nullable();
            
        });

        Schema::table('time_in_lesson', function($table) {
            $table->foreign('topic_id')->references('id')->on('topics');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_in_lesson', function (Blueprint $table) {
            //
        });
    }
}
