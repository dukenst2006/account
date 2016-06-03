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
            $table->string('slug')->unique();
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->integer('season_id')->unsigned();
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->boolean('active');
            $table->string('name', 128)->unique();
            $table->date('start');
            $table->date('end');
            $table->date('registration_start');
            $table->date('registration_end');
            $table->text('details')->nullable();
            $table->tinyInteger('max_teams')->unsigned();
            $table->date('lock_teams')->nullable();
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('event_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('participant_type', 16);
            $table->string('name', 64)->unique();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('events', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('event_type_id')->unsigned();
            $table->foreign('event_type_id')->references('id')->on('event_types');
            $table->float('price_per_participant')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
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
