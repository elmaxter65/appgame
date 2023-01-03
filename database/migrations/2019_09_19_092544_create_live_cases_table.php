<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('main_img');
            $table->text('patient_name');
            $table->text('patient_age');
            $table->text('patient_sex');
            $table->text('med_history');
            $table->text('symptoms');
            $table->enum('difficulty_level',['0','0.5','1','1.5','2','2.5','3','3.5','4','4.5','5']);
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
        Schema::dropIfExists('live_cases');
    }
}
