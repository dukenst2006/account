<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('name', 32);
			$table->string('address_one', 255);
			$table->string('address_two', 255)->nullable();
			$table->string('city', 64)->nullable();
			$table->string('state', 2)->nullable();
			$table->string('zip_code', 16);
			$table->double('latitude')->nullable();
			$table->double('longitude')->nullable();
			$table->timestamp('updated_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
			$table->softDeletes();
		});

	    Schema::table('users', function(Blueprint $table)
        {
          $table->integer('primary_address_id')->after('last_name')->unsigned()->nullable();
          $table->foreign('primary_address_id')->references('id')->on('addresses');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table)
        {
          $table->dropForeign('users_primary_address_id_foreign');
        });

        Schema::table('users', function(Blueprint $table)
        {
          $table->dropColumn('primary_address_id');
        });

		Schema::drop('addresses');
	}

}
