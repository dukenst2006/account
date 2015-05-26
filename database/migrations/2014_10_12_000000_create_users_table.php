<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			// a lot of fields are nullabe since accounts
			// are initially created with just email/password
			$table->increments('id');
			$table->boolean('status');
			$table->string('guid')->unique()->index();
			$table->string('email', 255)->unique();
			$table->string('first_name', 32)->nullable();
			$table->string('last_name', 32)->nullable();
			$table->string('phone', 10)->nullable();
			$table->string('gender', 1)->nullable();
			$table->string('avatar', 255);
			$table->dateTime('last_login')->nullable();
			$table->string('password', 60)->nullable();
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
