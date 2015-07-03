<?php

use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\Address;
use BibleBowl\Groups\GroupCreator;
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

        $season = Season::create([
            'name' => date('Y').'-'.(date('y')+1)
        ]);

        // ---------- Seed a Guardian
        $BKuhlGuardian = User::create([
            'status'			=> User::STATUS_CONFIRMED,
            'first_name'		=> 'Ben',
            'last_name'			=> 'Guardian',
            'email'				=> 'benkuhl+guardian@gmail.com',
            'password'			=> bcrypt('changeme')
        ]);
		$addresses = ['Home', 'Work', 'Church', 'Vacation Home'];
		foreach ($addresses as $key => $name) {
			$address = App::make('BibleBowl\Address', [[
				'name'			=> $name,
				'address_one'	=> '123 Test Street',
				'address_two'	=> ($key%2 == 0) ? 'Apt 5' : null, //for every other address
				'city'			=> 'Louisville',
				'state'			=> 'KY',
				'zip_code'		=> '40241'
			]]);
			$BKuhlGuardian->addresses()->save($address);
		}

        $this->seedHeadCoach();

		$this->call('AcceptanceTestingSeeder');

        self::$isSeeding = false;
    }

    private function seedHeadCoach()
    {
        $BKuhlHeadCoach = User::create([
            'status'			=> User::STATUS_CONFIRMED,
            'first_name'		=> 'Ben',
            'last_name'			=> 'HeadCoach',
            'email'				=> 'benkuhl+headcoach@gmail.com',
            'password'			=> bcrypt('changeme')
        ]);
        $address = App::make(Address::class, [[
            'name'			=> 'Home',
            'address_one'	=> '11025 Eagles Cove Dr.',
            'latitude'      => '38.2515659',
            'longitude'     => '-85.615241',
            'city'			=> 'Louisville',
            'state'			=> 'KY',
            'zip_code'		=> '40241'
        ]]);
        $BKuhlHeadCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($BKuhlHeadCoach, [
            'name'          => 'Southeast Christian Church',
            'type'          => Group::TYPE_TEEN,
            'address_id'    => $address->id
        ]);
    }

}
