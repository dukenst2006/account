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
			$table->text('settings')->nullable();
			$table->rememberToken();
			$table->timestamp('updated_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
		});

		/**
		 * How did you hear about Bible Bowl?
		 *  - Friend
		 *  - Church brochure/bulletin
		 *  - Homeschool convention
		 *  - TV
		 *  - Web Advertisement
		 *  - Internet
		 *  - Other
		 *
		 * Which of the following was most influential in your decision to join Bible Bowl?
		 *  - Friend's recommendation
		 *  - Attending a practice/demo/meeting
		 *  - Learning about it on the web site
		 *  - Homeschool curriculum potential
		 *  - Other
		 */
		Schema::create('user_survey_questions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('question');
			$table->smallInteger('order');
			$table->timestamp('updated_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
		});
		Schema::create('user_survey_answers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('question_id')->unsigned()->nullable();
			$table->foreign('question_id')->references('id')->on('user_survey_questions');
			$table->string('answer');
			$table->smallInteger('order');
			$table->timestamp('updated_at')->nullable();
			$table->timestamp('created_at')->useCurrent();
		});
		Schema::create('user_surveys', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('answer_id')->unsigned()->nullable();
			$table->foreign('answer_id')->references('id')->on('user_survey_answers');
			$table->string('other', 255)->nullable();
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
		Schema::drop('user_surveys');
		Schema::drop('user_survey_answers');
		Schema::drop('user_survey_questions');
		Schema::drop('users');
	}

}
