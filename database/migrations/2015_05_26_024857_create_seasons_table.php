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
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('player_season', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('season_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->boolean('paid');
            $table->string('grade');
            $table->string('shirt_size', 3);
            $table->timestamp('inactive')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unique(['season_id', 'player_id']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('player_season');
        Schema::drop('seasons');
	}

}
