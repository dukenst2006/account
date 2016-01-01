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
			$table->bigInteger('phone')->unsigned()->nullable();
			$table->string('gender', 1)->nullable();
			$table->string('avatar', 255);
			$table->timestamp('last_login')->nullable();
			$table->string('password', 60)->nullable();
			$table->json('settings')->nullable();
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
