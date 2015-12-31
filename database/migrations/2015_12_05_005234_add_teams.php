<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_sets', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->integer('season_id')->unsigned();
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->string('name', 64);
            $table->timestamps();
        });

        Schema::create('teams', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('team_set_id')->unsigned();
            $table->foreign('team_set_id')->references('id')->on('team_sets');
            $table->string('name', 64);
            $table->timestamps();
        });

        Schema::create('team_player', function(Blueprint $table)
        {
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->integer('player_id')->unsigned();
            $table->foreign('player_id')->references('id')->on('players');
            $table->tinyInteger('order')->unsigned();
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
        Schema::drop('team_player');
        Schema::drop('teams');
        Schema::drop('team_sets');
    }
}
