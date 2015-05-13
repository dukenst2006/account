<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		\BibleBowl\User::create([
			'first_name'		=> 'John',
			'last_name'			=> 'Smith',
			'email'				=> 'tester@testerson.com',
			'password'			=> bcrypt('asdf')
		]);
	}

}
