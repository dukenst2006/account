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
            $table->text('fees')->nullable();
            $table->tinyInteger('max_teams')->unsigned();
            $table->date('lock_teams')->nullable();
            $table->date('earlybird_ends')->nullable();
            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('participant_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 24)->unique();
            $table->string('description', 128);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('participant_fees', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('participant_type_id')->unsigned();
            $table->foreign('participant_type_id')->references('id')->on('participant_types');
            $table->boolean('requires_registration');
            $table->float('earlybird_fee')->nullable();
            $table->float('fee')->nullable();
            $table->float('onsite_fee')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['tournament_id', 'participant_type_id']);
        });

        Schema::create('event_types', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('participant_type_id')->unsigned();
            $table->foreign('participant_type_id')->references('id')->on('participant_types');
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
        Schema::drop('participant_fees');
        Schema::drop('tournaments');
        Schema::drop('participant_types');
    }
}
