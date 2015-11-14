<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use BibleBowl\TournamentCategory;
use BibleBowl\EventType;

class CreateTournamentsInfrastructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('guid')->unique();
            $table->integer('season_id')->unsigned();
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->string('name', 128)->unique();
            $table->boolean('active');
            $table->date('start');
            $table->date('end');
            $table->date('registration_start');
            $table->date('registration_end');
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('event_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('participant_type', 16);
            $table->string('name', 64)->unique();
            $table->timestamps();
        });

        Schema::create('events', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('event_type_id')->unsigned();
            $table->foreign('event_type_id')->references('id')->on('event_types');
            $table->float('price_per_participant')->nullable();
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
        Schema::drop('events');
        Schema::drop('event_types');
        Schema::drop('tournaments');
    }
}
