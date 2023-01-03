<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamic8sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic8s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dynamic_number');
            $table->integer('game_id');
            $table->mediumText('title')->nullable();
            $table->mediumText('question')->nullable();
            $table->mediumText('text_before')->nullable();
            $table->mediumText('text_after')->nullable();
            $table->mediumText('feedback_ok')->nullable();
            $table->mediumText('feedback_ko')->nullable();
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
        Schema::dropIfExists('dynamic8s');
    }
}
