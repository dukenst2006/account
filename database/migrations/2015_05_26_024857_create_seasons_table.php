<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('seasons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 10)->unique();
            $table->timestamps();
        });

        Schema::create('season_players', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('season_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->string('grade');
            $table->string('shirt_size', 3);
            $table->timestamps();
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->foreign('player_id')->references('id')->on('players');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('season_players');
        Schema::drop('seasons');
	}

}
