<?php

use BibleBowl\Address;
use BibleBowl\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AcceptanceTestingSeeder extends Seeder {

	const USER_FIRST_NAME = 'Joe';
	const USER_LAST_NAME = 'Walters';
	const USER_EMAIL = 'joe.walters@example.com';
	const USER_PASSWORD = 'changeme';
	const UNCONFIRMED_USER_EMAIL = 'unconfirmed-joe.walters@example.com';

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        $homeAddress = Address::create([
            'name'			=> 'Home',
            'address_one'	=> 'Acceptance Test Seeder Street',
            'address_two'	=> (rand(0, 5)) ? 'Apt 5' : null, //for every other address
            'city'			=> 'Louisville',
            'state'			=> 'KY',
            'zip_code'		=> '40241'
        ]);

        $TestUser = User::create([
          'status'			    => User::STATUS_CONFIRMED,
          'first_name'		    => self::USER_FIRST_NAME,
          'last_name'			=> self::USER_LAST_NAME,
          'email'				=> self::USER_EMAIL,
          'password'			=> bcrypt(self::USER_PASSWORD),
          'primary_address_id'  => $homeAddress->id,
        ]);
		$TestUser->addresses()->save($homeAddress);

		// used for asserting you can't login without being confirmed
		User::create([
			'first_name'		    => self::USER_FIRST_NAME.'-unconfirmed',
			'last_name'			    => self::USER_LAST_NAME,
			'email'				    => self::UNCONFIRMED_USER_EMAIL,
			'password'			    => bcrypt(self::USER_PASSWORD),
		  	'primary_address_id'    => $homeAddress->id,
		]);
	}
}
