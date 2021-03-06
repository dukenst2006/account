<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersToTournaments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_coordinators', function (Blueprint $table) {
            $table->integer('tournament_id')->unsigned();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->integer('coordinator_id')->unsigned();
            $table->foreign('coordinator_id')->references('id')->on('users');
            $table->timestamps();
            $table->unique(['tournament_id', 'coordinator_id']);
        });
        Schema::table('invitations', function ($table) {
            $table->integer('group_id')->unsigned()->nullable()->change();
            $table->integer('tournament_id')->unsigned()->nullable()->after('group_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tournament_coordinators');
        Schema::table('invitations', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->change();
            $table->dropForeign('invitations_tournament_id_foreign');
            $table->dropColumn('tournament_id');
        });
    }
}
