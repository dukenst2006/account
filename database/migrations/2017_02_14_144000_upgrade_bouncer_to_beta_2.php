<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Silber\Bouncer\Database\Models;

class UpgradeBouncerToBeta2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Models::table('abilities'), function (Blueprint $table) {
            $table->boolean('only_owned')->default(false)->after('entity_type');
            $table->dropIndex('abilities_name_entity_id_entity_type_unique');
            $table->unique(
                ['name', 'entity_id', 'entity_type', 'only_owned'],
                'abilities_unique_index'
            );
        });

        Schema::table(Models::table('roles'), function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
            $table->integer('level')->unsigned()->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Models::table('abilities'), function (Blueprint $table) {
            $table->dropColumn('only_owned');
            $table->dropIndex('abilities_unique_index');
            $table->unique(['name', 'entity_id', 'entity_type']);
        });

        Schema::table(Models::table('roles'), function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('level');
        });
    }
}
