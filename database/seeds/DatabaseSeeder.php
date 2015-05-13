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
			'first_name'		=> 'Ben',
			'last_name'			=> 'Kuhl',
			'email'				=> 'benkuhl@gmail.com',
			'password'			=> bcrypt('changeme')
		]);
	}

}
