<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeedbackToDynamic1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dynamic1s', function (Blueprint $table) {
            $table->mediumText('feedback_ok')->after('correct_answer');
            $table->mediumText('feedback_ko')->after('feedback_ok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dynamic1s', function (Blueprint $table) {
            //
        });
    }
}
