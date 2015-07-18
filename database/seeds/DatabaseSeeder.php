<?php

use BibleBowl\Players\PlayerCreator;
use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\Address;
use BibleBowl\Groups\GroupCreator;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    private static $isSeeding = false;

    /**
     * @return bool
     */
    public static function isSeeding()
    {
        return self::$isSeeding;
    }

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        self::$isSeeding = true;

		Model::unguard();

        Season::create([
            'name' => date('Y').'-'.(date('y')+1)
        ]);

        $this->seedGuardian();
        $this->seedHeadCoach();

		$this->call('AcceptanceTestingSeeder');

        self::$isSeeding = false;
    }

    private function seedHeadCoach()
    {
        $address = Address::create([
            'name'			=> 'Home',
            'address_one'	=> '11025 Eagles Cove Dr.',
            'address_two'   => null,
            'latitude'      => '38.2515659',
            'longitude'     => '-85.615241',
            'city'			=> 'Louisville',
            'state'			=> 'KY',
            'zip_code'		=> '40241'
        ]);
        $BKuhlHeadCoach = User::create([
          'status'			    => User::STATUS_CONFIRMED,
          'first_name'		    => 'Ben',
          'last_name'			=> 'HeadCoach',
          'email'				=> 'benkuhl+headcoach@gmail.com',
          'password'			=> bcrypt('changeme'),
          'primary_address_id'  => $address->id
        ]);
        $BKuhlHeadCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($BKuhlHeadCoach, [
            'name'                  => 'Southeast Christian Church',
            'type'                  => Group::TYPE_TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id
        ]);

        $BKuhlHeadCoach = User::findOrFail($BKuhlHeadCoach->id);
        $groupCreator->create($BKuhlHeadCoach, [
            'name'                  => 'Mount Pleasant Christian Church',
            'type'                  => Group::TYPE_TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id
        ]);
    }

    private function seedGuardian()
    {
        $faker = Factory::create();
        $addresses = ['Home', 'Work', 'Church', 'Vacation Home'];
        $saved_addresses = array();

        foreach ($addresses as $key => $name) {
            $address = Address::create([
              'name'			=> $name,
              'address_one'	    => $faker->buildingNumber . ' ' . $faker->streetName . ' ' . $faker->streetSuffix,
              'address_two'	    => (rand(0, 5)) ? $faker->secondaryAddress : null, // randomized
              'latitude'        => $faker->latitude,
              'longitude'       => $faker->longitude,
              'city'			=> 'Louisville',
              'state'			=> 'KY',
              'zip_code'		=> '40241'
            ]);
            $saved_addresses[] = $address;
        }

        $BKuhlGuardian = User::create([
            'status'			  => User::STATUS_CONFIRMED,
            'first_name'		  => 'Ben',
            'last_name'			  => 'Guardian',
            'email'				  => 'benkuhl+guardian@gmail.com',
            'password'			  => bcrypt('changeme'),
            'primary_address_id'  => $address->id
        ]);
        $BKuhlGuardian->addresses()->saveMany($saved_addresses);

        // Generate fake player information.
        $num_players = 5;
        for ($i = 0; $i < $num_players; $i++) {
            $BKuhlGuardian = User::find($BKuhlGuardian->id);
            $playerCreator = App::make(PlayerCreator::class);
            $playerCreator->create($BKuhlGuardian, [
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'gender'        => (rand(0, 1)) ? 'M' : 'F',
                'birthday'      => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y')
            ]);
        }
    }

}
