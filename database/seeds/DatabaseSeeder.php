<?php

use BibleBowl\Player;
use BibleBowl\Players\PlayerCreator;
use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\Address;
use BibleBowl\Groups\GroupCreator;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    private static $isSeeding = false;

    const GROUP_NAME = 'Mount Pleasant Christian Church';
    const HEAD_COACH_EMAIL = 'benkuhl+headcoach@gmail.com';
    const GUARDIAN_EMAIL = 'benkuhl+guardian@gmail.com';

    /** @var Season */
    private $season;

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
        // load ModelFactory.php so functions can be used later
        factory(User::class);

        self::$isSeeding = true;

		Model::unguard();

        $this->season = Season::create([
            'name' => date('Y').'-'.(date('y')+1)
        ]);

        $this->seedGuardian();
        $this->seedHeadCoach();

        $this->call('AcceptanceTestingSeeder');
        $this->call('StagingSeeder');

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
          'email'				=> self::HEAD_COACH_EMAIL,
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

        $address = factory(Address::class)->create([
            'name'      => 'Church',
            'latitude'  => '38.316334',
            'longitude' => '-85.573143',
        ]);
        $BKuhlHeadCoach->addresses()->save($address);
        $BKuhlHeadCoach = User::findOrFail($BKuhlHeadCoach->id);
        $this->seedGroupWithPlayers($groupCreator, $BKuhlHeadCoach, $address);
    }

    private function seedGuardian()
    {
        $faker = Factory::create();
        $addresses = ['Home', 'Work', 'Church', 'Vacation Home'];
        $savedAddresses = [];
        foreach ($addresses as $key => $name) {
            $savedAddresses[] = factory(Address::class)->create([
                'name' => $name
            ]);
        }

        $BKuhlGuardian = User::create([
            'status'			    => User::STATUS_CONFIRMED,
            'first_name'		    => 'Ben',
            'last_name'			    => 'Guardian',
            'email'				    => self::GUARDIAN_EMAIL,
            'password'			    => bcrypt('changeme'),
            'primary_address_id'    => $savedAddresses[0]->id
        ]);
        $BKuhlGuardian->addresses()->saveMany($savedAddresses);

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

    private function seedGroupWithPlayers(GroupCreator $groupCreator, User $headCoach, Address $address)
    {
        $group = $groupCreator->create($headCoach, [
            'name'                  => self::GROUP_NAME,
            'type'                  => Group::TYPE_TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id
        ]);

        $shirtSizes = ['S', 'YS', 'M', 'L', 'YL', 'YM'];
        $guardian = seedGuardian([], [
            'latitude'  => '38.301815',
            'longitude' => '-85.597701',
        ]);
        for($x = 0; $x <= 2; $x++)
        {
            $player = seedPlayer($guardian);
            $this->season->players()->attach($player->id, [
                'group_id'      => $group->id,
                'grade'         => rand(6, 12),
                'shirt_size'    => $shirtSizes[array_rand($shirtSizes)]
            ]);
        }

        # Seed inactive player
        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'inactive'      => Carbon::now()->toDateTimeString(),
            'group_id'      => $group->id,
            'grade'         => rand(6, 12),
            'shirt_size'    => $shirtSizes[array_rand($shirtSizes)]
        ]);
    }

}
