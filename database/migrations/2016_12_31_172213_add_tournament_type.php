<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTournamentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournament_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->unsignedInteger('tournament_type_id')->after('id')->nullable();
            $table->foreign('tournament_type_id')->references('id')->on('tournament_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropIndex('tournaments_tournament_type_id_foreign');
            $table->dropColumn('tournament_type_id');
        });
        Schema::drop('tournament_types');
    }
}
