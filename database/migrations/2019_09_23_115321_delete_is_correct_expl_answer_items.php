<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteIsCorrectExplAnswerItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exploration_answer_items', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });
        Schema::table('treatment_answers', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });
        Schema::table('stent_answers', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exploration_answer_items', function (Blueprint $table) {
            //
        });
    }
}
