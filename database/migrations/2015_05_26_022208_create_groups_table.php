<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('guid')->unique()->index();
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->string('name');
			$table->integer('owner_id')->unsigned();
			$table->foreign('owner_id')->references('id')->on('users');
			$table->timestamp('inactive')->nullable();
			$table->integer('address_id')->unsigned();
			$table->foreign('address_id')->references('id')->on('addresses');
			$table->integer('meeting_address_id')->unsigned();
			$table->foreign('meeting_address_id')->references('id')->on('addresses');
			$table->timestamp('updated_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
		});

        Schema::create('group_user', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('groups');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('group_user');
        Schema::drop('groups');
	}

}
