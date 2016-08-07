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

            // make original columns nullable
            $table->string('first_name', 32)->nullable()->change();
            $table->string('last_name', 32)->nullable()->change();
            $table->string('email', 128)->nullable()->change();
            $table->string('gender', 1)->nullable()->change();
            $table->string('spouse_first_name', 32)->nullable()->change();
            $table->string('spouse_gender', 1)->nullable()->change();

            $table->string('shirt_size', 3)->nullable()->after('gender');
            $table->string('spouse_shirt_size', 3)->nullable()->after('spouse_gender');
            $table->integer('address_id')->unsigned()->nullable()->after('spouse_shirt_size');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->dropColumn('spouse_last_name');
        });

        Schema::table('tournament_spectator_minors', function(Blueprint $table)
        {
            $table->string('shirt_size', 3)->after('age');
            $table->string('gender', 1)->after('shirt_size');
        });

        Schema::table('carts', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned()->nullable()->change();
        });

        Schema::table('receipts', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned()->nullable()->change();
            $table->integer('address_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned()->change();
            $table->integer('address_id')->unsigned()->change();
        });

        Schema::table('carts', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned()->change();
        });

        Schema::table('tournament_spectator_minors', function(Blueprint $table)
        {
            $table->dropColumn('shirt_size');
            $table->dropColumn('gender');
        });

        Schema::table('tournament_spectators', function(Blueprint $table)
        {
            $table->dropForeign('tournament_spectators_group_id_foreign');
            $table->dropForeign('tournament_spectators_address_id_foreign');
            $table->dropColumn('group_id');
            $table->dropColumn('address_id');
            $table->dropColumn('shirt_size');
            $table->dropColumn('spouse_shirt_size');

            // make columns not nullable
            $table->string('first_name', 32)->change();
            $table->string('last_name', 32)->change();
            $table->string('email', 128)->change();
            $table->string('gender', 1)->change();
            $table->string('spouse_first_name', 32)->change();
            $table->string('spouse_gender', 1)->change();

            $table->string('spouse_last_name', 32)->after('spouse_first_name');
        });
    }
}
