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
            $table->timestamps();
        });

        Program::create([
            'name'          => 'Beginner Bible Bowl',
            'abbreviation'  => 'Beginner',
            'slug'          => 'beginner',
            'description'   => 'Grades 3 - 5'
        ]);

        Program::create([
            'name'          => 'Teen Bible Bowl',
            'abbreviation'  => 'Teen',
            'slug'          => 'teen',
            'description'   => 'Grades 6 - 12'
        ]);
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
