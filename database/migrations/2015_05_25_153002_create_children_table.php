<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildrenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('children', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('guid')->unique()->index();
			$table->string('first_name', 32);
			$table->string('last_name', 32);
			$table->string('shirt_size', 3);
			$table->string('gender', 1);
			$table->date('birthday');
			$table->integer('guardian_id')->unsigned();
			$table->foreign('guardian_id')->references('id')->on('users');
			$table->softDeletes();
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
		Schema::drop('children');
	}

}
