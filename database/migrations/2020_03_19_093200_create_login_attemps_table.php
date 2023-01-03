<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginAttempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_attemps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('num_attemps')->default(0);
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->bigInteger('user_cms_id')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
        Schema::table('login_attemps', function($table) {
            $table->foreign('user_id')->references('id')->on('user_apps');
            $table->foreign('user_cms_id')->references('id')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_attemps');
    }
}
