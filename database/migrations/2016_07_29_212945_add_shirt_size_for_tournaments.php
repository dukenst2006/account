<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShirtSizeForTournaments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tournament_spectators', function(Blueprint $table)
        {
            $table->integer('group_id')->unsigned()->nullable()->after('tournament_id');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->string('shirt_size', 3)->after('gender');
            $table->string('spouse_shirt_size', 3)->after('spouse_gender');
            $table->dropColumn('spouse_last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_spectators', function(Blueprint $table)
        {
            $table->dropColumn('group_id');
            $table->dropColumn('shirt_size');
            $table->dropColumn('spouse_shirt_size');
            $table->string('spouse_last_name', 32)->after('spouse_first_name');
        });
    }
}
