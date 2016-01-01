<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('players', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('guid')->unique()->index();
			$table->integer('guardian_id')->unsigned();
			$table->foreign('guardian_id')->references('id')->on('users');
			$table->string('first_name', 32);
			$table->string('last_name', 32);
			$table->string('gender', 1);
			$table->date('birthday');
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
		Schema::drop('players');
	}

}
