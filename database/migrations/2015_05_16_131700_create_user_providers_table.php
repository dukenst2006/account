<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProvidersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_providers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('provider');
			$table->string('provider_id');
			$table->timestamps();
			$table->unique(['provider', 'provider_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_providers', function (Blueprint $table) {
			$table->dropForeign('user_providers_user_id_foreign');
			$table->dropUnique('user_providers_provider_provider_id_unique');
		});
		Schema::drop('user_providers');
	}

}
