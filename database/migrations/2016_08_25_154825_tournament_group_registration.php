<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class TournamentGroupRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedInteger('receipt_id')->nullable()->after('team_set_id');
            $table->foreign('receipt_id')->references('id')->on('receipts');
        });

        Schema::table('team_sets', function (Blueprint $table) {
            $table->unsignedInteger('tournament_id')->nullable()->after('season_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments');
        });
        Schema::table('event_players', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->rename('event_player');
        });
        Schema::table('tournament_spectators', function (Blueprint $table) {
            $table->string('guid')->after('id')->unique()->index();
            $table->unsignedInteger('registered_by')->nullable()->after('group_id');
            $table->foreign('registered_by')->references('id')->on('users');
            $table->unsignedBigInteger('phone')->nullable()->after('email');
        });
        Schema::table('tournament_quizmasters', function (Blueprint $table) {
            $table->unsignedInteger('registered_by')->nullable()->after('group_id');
            $table->foreign('registered_by')->references('id')->on('users');
            $table->unsignedBigInteger('phone')->after('email');
            $table->string('shirt_size', 3)->nullable()->change();
        });
        Schema::table('tournaments', function (Blueprint $table) {
            $table->text('settings')->after('earlybird_ends')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournament_quizmasters', function (Blueprint $table) {
            $table->dropForeign('tournament_quizmasters_registered_by_foreign');
            $table->dropColumn('registered_by');
            $table->dropColumn('phone');
            $table->string('shirt_size', 3)->change();
        });
        Schema::table('tournament_spectators', function (Blueprint $table) {
            $table->dropForeign('tournament_spectators_registered_by_foreign');
            $table->dropColumn('registered_by');
            $table->dropColumn('phone');
            $table->dropColumn('guid');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign('teams_receipt_id_foreign');
            $table->dropColumn('receipt_id');
        });

        Schema::table('team_sets', function (Blueprint $table) {
            $table->dropForeign('team_sets_tournament_id_foreign');
            $table->dropColumn('tournament_id');
        });
        Schema::table('event_player', function (Blueprint $table) {
            $table->increments('id');
            $table->rename('event_players');
        });
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('settings');
        });
    }
}
