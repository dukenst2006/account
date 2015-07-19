<?php

use BibleBowl\Player;
use BibleBowl\Address;
use BibleBowl\Players\PlayerCreator;
use BibleBowl\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AcceptanceTestingSeeder extends Seeder {

	const USER_FIRST_NAME = 'Joe';
	const USER_LAST_NAME = 'Walters';
	const USER_EMAIL = 'joe.walters@example.com';
	const USER_PASSWORD = 'changeme';

	const GUARDIAN_EMAIL    = 'testUser+guardian@gmail.com';
	const GUARDIAN_PASSWORD = 'changeme';

	const GUARDIAN_PLAYER_A_FULL_NAME = 'John Watson';
	const GUARDIAN_PLAYER_B_FULL_NAME = 'Emily Smith';
	const GUARDIAN_PLAYER_C_FULL_NAME = 'Alex Johnson';

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
            'address_one'	=> '123 Acceptance Test Seeder Street',
            'address_two'	=> null,
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

		$this->seedGuardian();
	}

    private function seedGuardian()
    {
        $addresses = ['Home', 'Work'];
        $testAddresses = array();

        foreach ($addresses as $key => $name) {
            $address = Address::create([
              'name'			=> $name,
              'address_one'	=> '123 Test Street',
              'address_two'	=> ($key%2 == 0) ? 'Apt 5' : null, //for every other address
              'city'			=> 'Louisville',
              'state'			=> 'KY',
              'zip_code'		=> '40241'
            ]);
            $testAddresses[] = $address;
        }

        $testGuardian = User::create([
          'status'			    => User::STATUS_CONFIRMED,
          'first_name'		    => 'Test',
          'last_name'			    => 'Guardian',
          'email'				    => self::GUARDIAN_EMAIL,
          'password'			    => bcrypt(self::GUARDIAN_PASSWORD),
          'primary_address_id'    => $address->id
        ]);
        $testGuardian->addresses()->saveMany($testAddresses);


        $playerCreator = App::make(PlayerCreator::class);
        $playerCreator->create($testGuardian, [
          'first_name'    => 'John',
          'last_name'     => 'Watson',
          'gender'        => 'M',
          'birthday'      => '2/24/1988'
        ]);
        $testGuardian = User::find($testGuardian->id);
        $playerCreator->create($testGuardian, [
          'first_name'    => 'Emily',
          'last_name'     => 'Smith',
          'gender'        => 'F',
          'birthday'      => '2/24/1986'
        ]);
        $playerCreator->create($testGuardian, [
          'first_name'    => 'Alex',
          'last_name'     => 'Johnson',
          'gender'        => 'M',
          'birthday'      => '6/14/1987'
        ]);
    }
}
