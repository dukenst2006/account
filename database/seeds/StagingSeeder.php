<?php

use BibleBowl\Program;
use BibleBowl\Players\PlayerCreator;
use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Address;
use BibleBowl\Groups\GroupCreator;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use BibleBowl\Role;

class StagingSeeder extends Seeder {

    /** @var Season */
    private $season;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $this->season = Season::orderBy('id', 'DESC')->first();

        $this->updateMailchimpIds();

        $this->seedJosiahDirector();
        $this->seedJosiahGuardian();
        $this->seedJosiahHeadCoach();

        $this->seedKeithBoardMember();
        $this->seedKeithGuardian();
        $this->seedKeithHeadCoach();
    }

    private function seedJosiahDirector()
    {
        $address = Address::create([
            'name'			=> 'Home',
            'address_one'	=> '178 Balsam Ct',
            'address_two'   => null,
            'latitude'      => '39.29114',
            'longitude'     => '-84.476249',
            'city'			=> 'Cincinnati',
            'state'			=> 'OH',
            'zip_code'		=> '45246'
        ]);
        $director = User::create([
            'status'			=> User::STATUS_CONFIRMED,
            'first_name'		=> 'Josiah',
            'last_name'			=> 'Director',
            'email'				=> 'jgorman+director@biblebowl.org',
            'password'			=> bcrypt('changeme'),
            'primary_address_id'  => $address->id
        ]);
        $director->addresses()->save($address);

        $role = Role::findOrFail(Role::DIRECTOR_ID);
        $director->attachRole($role);
    }

    private function seedJosiahHeadCoach()
    {
        $address = Address::create([
            'name'			=> 'Home',
            'address_one'	=> '178 Balsam Ct',
            'address_two'   => null,
            'latitude'      => '39.29114',
            'longitude'     => '-84.476249',
            'city'			=> 'Cincinnati',
            'state'			=> 'OH',
            'zip_code'		=> '45246'
        ]);
        $headCoach = User::create([
            'status'			    => User::STATUS_CONFIRMED,
            'first_name'		    => 'Josiah',
            'last_name'			=> 'HeadCoach',
            'email'				=> 'jgorman+headcoach@biblebowl.org',
            'password'			=> bcrypt('biblebowl'),
            'primary_address_id'  => $address->id
        ]);
        $headCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($headCoach, [
            'name'                  => 'Cincinnati Homeschoolers',
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id
        ]);

        $address = factory(Address::class)->create([
            'name'      => 'Church',
            'latitude'  => '39.285121',
            'longitude' => '-84.4721087',
        ]);
        $headCoach->addresses()->save($address);
        $headCoach = User::findOrFail($headCoach->id);
        $this->seedGroupWithPlayers($groupCreator, $headCoach, $address, 'JGorman Christian Church', '39.2870974', '-84.4704672');
    }

    private function seedJosiahGuardian()
    {
        $faker = Factory::create();
        $addresses = ['Home', 'Work', 'Church'];
        $savedAddresses = [];
        foreach ($addresses as $key => $name) {
            $savedAddresses[] = factory(Address::class)->create([
                'name' => $name
            ]);
        }

        $guardian = User::create([
            'status'			    => User::STATUS_CONFIRMED,
            'first_name'		    => 'Josiah',
            'last_name'			    => 'Guardian',
            'email'				    => 'jgorman+guardian@biblebowl.org',
            'password'			    => bcrypt('biblebowl'),
            'primary_address_id'    => $savedAddresses[0]->id
        ]);
        $guardian->addresses()->saveMany($savedAddresses);

        // Generate fake player information.
        $num_players = 5;
        for ($i = 0; $i < $num_players; $i++) {
            $guardian = User::find($guardian->id);
            $playerCreator = App::make(PlayerCreator::class);

            $playerCreator->create($guardian, [
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'gender'        => (rand(0, 1)) ? 'M' : 'F',
                'birthday'      => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y')
            ]);
        }
    }

    private function seedKeithBoardMember()
    {
        $address = Address::create([
            'name'			=> 'Home',
            'address_one'	=> '5900 Casa Del Rey Cir',
            'address_two'   => null,
            'latitude'      => '28.470933',
            'longitude'     => '-81.425187',
            'city'			=> 'Orlando',
            'state'			=> 'FL',
            'zip_code'		=> '32809'
        ]);
        $boardMember = User::create([
            'status'			=> User::STATUS_CONFIRMED,
            'first_name'		=> 'Josiah',
            'last_name'			=> 'Director',
            'email'				=> 'ksmith+boardmember@biblebowl.org',
            'password'			=> bcrypt('changeme'),
            'primary_address_id'  => $address->id
        ]);
        $boardMember->addresses()->save($address);

        $role = Role::findOrFail(Role::BOARD_MEMBER_ID);
        $boardMember->attachRole($role);
    }

    private function seedKeithHeadCoach()
    {
        $address = Address::create([
            'name'			=> 'Home',
            'address_one'	=> '5900 Casa Del Rey Cir',
            'address_two'   => null,
            'latitude'      => '28.470933',
            'longitude'     => '-81.425187',
            'city'			=> 'Orlando',
            'state'			=> 'FL',
            'zip_code'		=> '32809'
        ]);
        $headCoach = User::create([
            'status'			    => User::STATUS_CONFIRMED,
            'first_name'		    => 'Keith',
            'last_name'			    => 'HeadCoach',
            'email'				    => 'ksmith+headcoach@biblebowl.org',
            'password'			    => bcrypt('biblebowl'),
            'primary_address_id'  => $address->id
        ]);
        $headCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($headCoach, [
            'name'                  => 'Florida Homeschoolers',
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id
        ]);

        $address = factory(Address::class)->create([
            'name'      => 'Church',
            'latitude'  => '39.285121',
            'longitude' => '-84.4721087',
        ]);
        $headCoach->addresses()->save($address);
        $headCoach = User::findOrFail($headCoach->id);
        $this->seedGroupWithPlayers($groupCreator, $headCoach, $address, 'KSmith Christian Church', '28.472610', '-81.418877');
    }

    private function seedKeithGuardian()
    {
        $faker = Factory::create();
        $addresses = ['Home', 'Work', 'Church'];
        $savedAddresses = [];
        foreach ($addresses as $key => $name) {
            $savedAddresses[] = factory(Address::class)->create([
                'name' => $name
            ]);
        }

        $guardian = User::create([
            'status'			    => User::STATUS_CONFIRMED,
            'first_name'		    => 'Keith',
            'last_name'			    => 'Guardian',
            'email'				    => 'ksmith+guardian@biblebowl.org',
            'password'			    => bcrypt('biblebowl'),
            'primary_address_id'    => $savedAddresses[0]->id
        ]);
        $guardian->addresses()->saveMany($savedAddresses);

        // Generate fake player information.
        $num_players = 5;
        for ($i = 0; $i < $num_players; $i++) {
            $guardian = User::find($guardian->id);
            $playerCreator = App::make(PlayerCreator::class);

            $playerCreator->create($guardian, [
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'gender'        => (rand(0, 1)) ? 'M' : 'F',
                'birthday'      => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y')
            ]);
        }
    }

    private function seedGroupWithPlayers(GroupCreator $groupCreator, User $headCoach, Address $address, $groupName, $guardianLat, $guardianLng)
    {
        $group = $groupCreator->create($headCoach, [
            'name'                  => $groupName,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id
        ]);

        $shirtSizes = ['S', 'YS', 'M', 'L', 'YL', 'YM'];
        $guardian = seedGuardian([], [
            'latitude'  => $guardianLat,
            'longitude' => $guardianLng,
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

    /**
     * Update the mailchimp ids so they match the staging list instead of production
     */
    private function updateMailchimpIds()
    {
        Role::findOrFail(Role::LEAGUE_COORDINATOR_ID)->update([
            'mailchimp_interest_id' => '4548244911'
        ]);
        Role::findOrFail(Role::HEAD_COACH_ID)->update([
            'mailchimp_interest_id' => 'cea4f8e0dd'
        ]);
        Role::findOrFail(Role::COACH_ID)->update([
            'mailchimp_interest_id' => 'e11132acbf'
        ]);
        Role::findOrFail(Role::QUIZMASTER_ID)->update([
            'mailchimp_interest_id' => 'e58faebc7c'
        ]);
        Role::findOrFail(Role::GUARDIAN_ID)->update([
            'mailchimp_interest_id' => '295ac3a88c'
        ]);
    }

}
