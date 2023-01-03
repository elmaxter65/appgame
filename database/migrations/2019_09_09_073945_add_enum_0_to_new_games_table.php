<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnum0ToNewGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            Schema::table('games', function (Blueprint $table) {
                $table->dropColumn('dynamic_number');
                
            });
            Schema::table('games', function (Blueprint $table) {
                $table->enum('dynamic_number',['0','1','2','3','4','5','6','7','8','9','10','11'])->after('level');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            //
        });
    }
}
