<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemoryMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('player_season', function (Blueprint $table) {
            $table->unsignedTinyInteger('memory_master')->after('shirt_size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_season', function (Blueprint $table) {
            $table->dropColumn('memory_master');
        });
    }
}
