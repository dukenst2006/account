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

		$BKuhl = \BibleBowl\User::create([
			'first_name'		=> 'Ben',
			'last_name'			=> 'Kuhl',
			'email'				=> 'benkuhl@gmail.com',
			'password'			=> bcrypt('changeme')
		]);
		$addresses = ['Home', 'Work', 'Church', 'Vacation Home'];
		foreach ($addresses as $key => $name) {
			$homeAddress = App::make('BibleBowl\Address', [[
				'name'			=> $name,
				'first_name'	=> 'Ben',
				'last_name'		=> 'Kuhl',
				'address_one'	=> '123 Test Street',
				'address_two'	=> ($key%2 == 0) ? 'Apt 5' : null, //for every other address
				'city'			=> 'Louisville',
				'state'			=> 'KY',
				'zip_code'		=> '40241'
			]]);
			$BKuhl->addresses()->save($homeAddress);
		}

		$this->call('AcceptanceTestingSeeder');
	}

}
