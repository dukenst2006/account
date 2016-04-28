<?php

use BibleBowl\Program;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 65)->unique();
            $table->string('abbreviation', 10)->unique();
            $table->string('slug', 10)->unique();
            $table->string('description', 24);
            $table->decimal('registration_fee', '5', 2);
            $table->tinyInteger('min_grade')->unsigned();
            $table->tinyInteger('max_grade')->unsigned();
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
        Schema::drop('programs');
    }
}
