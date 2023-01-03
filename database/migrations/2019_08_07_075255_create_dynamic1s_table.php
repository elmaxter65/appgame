<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamic1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic1s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dynamic_number');
            $table->integer('game_id');
            $table->mediumText('title')->nullable();
            $table->mediumText('question');
            $table->mediumText('image_1');
            $table->mediumText('image_2');
            $table->boolean('correct_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic1s');
    }
}
