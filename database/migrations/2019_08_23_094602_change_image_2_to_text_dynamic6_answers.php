<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeImage2ToTextDynamic6Answers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dynamic6_answers', function (Blueprint $table) {
            $table->renameColumn('image_2', 'text');
            $table->dropColumn('correct_answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dynamic6_answers', function (Blueprint $table) {
            //
        });
    }
}
