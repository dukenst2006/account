<?php

use BibleBowl\Address;
use BibleBowl\Groups\GroupCreator;
use BibleBowl\GroupType;
use BibleBowl\Players\PlayerCreator;
use BibleBowl\Program;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class StagingSeeder extends Seeder
{
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
            'name'             => 'Home',
            'address_one'      => '178 Balsam Ct',
            'address_two'      => null,
            'latitude'         => '39.29114',
            'longitude'        => '-84.476249',
            'city'             => 'Cincinnati',
            'state'            => 'OH',
            'zip_code'         => '45246',
        ]);
        $director = User::create([
            'status'               => User::STATUS_CONFIRMED,
            'first_name'           => 'Josiah',
            'last_name'            => 'Director',
            'email'                => 'jgorman+admin@biblebowl.org',
            'password'             => bcrypt('changeme'),
            'primary_address_id'   => $address->id,
        ]);
        $director->addresses()->save($address);

        $director->assign(Role::ADMIN);
    }

    private function seedJosiahHeadCoach()
    {
        $address = Address::create([
            'name'             => 'Home',
            'address_one'      => '178 Balsam Ct',
            'address_two'      => null,
            'latitude'         => '39.29114',
            'longitude'        => '-84.476249',
            'city'             => 'Cincinnati',
            'state'            => 'OH',
            'zip_code'         => '45246',
        ]);
        $headCoach = User::create([
            'status'                => User::STATUS_CONFIRMED,
            'first_name'            => 'Josiah',
            'last_name'             => 'HeadCoach',
            'email'                 => 'jgorman+headcoach@biblebowl.org',
            'password'              => bcrypt('biblebowl'),
            'primary_address_id'    => $address->id,
        ]);
        $headCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($headCoach, [
            'name'                  => 'Cincinnati Homeschoolers',
            'group_type_id'         => GroupType::CHURCH,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id,
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
                'name' => $name,
            ]);
        }

        $guardian = User::create([
            'status'                   => User::STATUS_CONFIRMED,
            'first_name'               => 'Josiah',
            'last_name'                => 'Guardian',
            'email'                    => 'jgorman+guardian@biblebowl.org',
            'password'                 => bcrypt('biblebowl'),
            'primary_address_id'       => $savedAddresses[0]->id,
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
                'birthday'      => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y'),
            ]);
        }
    }

    private function seedKeithBoardMember()
    {
        $address = Address::create([
            'name'             => 'Home',
            'address_one'      => '5900 Casa Del Rey Cir',
            'address_two'      => null,
            'latitude'         => '28.470933',
            'longitude'        => '-81.425187',
            'city'             => 'Orlando',
            'state'            => 'FL',
            'zip_code'         => '32809',
        ]);
        $boardMember = User::create([
            'status'               => User::STATUS_CONFIRMED,
            'first_name'           => 'Josiah',
            'last_name'            => 'Director',
            'email'                => 'ksmith+boardmember@biblebowl.org',
            'password'             => bcrypt('changeme'),
            'primary_address_id'   => $address->id,
        ]);
        $boardMember->addresses()->save($address);

        $boardMember->assign(Role::BOARD_MEMBER);
    }

    private function seedKeithHeadCoach()
    {
        $address = Address::create([
            'name'             => 'Home',
            'address_one'      => '5900 Casa Del Rey Cir',
            'address_two'      => null,
            'latitude'         => '28.470933',
            'longitude'        => '-81.425187',
            'city'             => 'Orlando',
            'state'            => 'FL',
            'zip_code'         => '32809',
        ]);
        $headCoach = User::create([
            'status'                   => User::STATUS_CONFIRMED,
            'first_name'               => 'Keith',
            'last_name'                => 'HeadCoach',
            'email'                    => 'ksmith+headcoach@biblebowl.org',
            'password'                 => bcrypt('biblebowl'),
            'primary_address_id'       => $address->id,
        ]);
        $headCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($headCoach, [
            'name'                  => 'Florida Homeschoolers',
            'group_type_id'         => GroupType::HOMESCHOOL,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id,
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
                'name' => $name,
            ]);
        }

        $guardian = User::create([
            'status'                   => User::STATUS_CONFIRMED,
            'first_name'               => 'Keith',
            'last_name'                => 'Guardian',
            'email'                    => 'ksmith+guardian@biblebowl.org',
            'password'                 => bcrypt('biblebowl'),
            'primary_address_id'       => $savedAddresses[0]->id,
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
                'birthday'      => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y'),
            ]);
        }
    }

    private function seedGroupWithPlayers(GroupCreator $groupCreator, User $headCoach, Address $address, $groupName, $guardianLat, $guardianLng)
    {
        $group = $groupCreator->create($headCoach, [
            'name'                  => $groupName,
            'group_type_id'         => GroupType::CHURCH,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id,
        ]);

        $guardian = seedGuardian([], [
            'latitude'  => $guardianLat,
            'longitude' => $guardianLng,
        ]);
        for ($x = 0; $x <= 2; $x++) {
            $player = seedPlayer($guardian);
            $this->season->players()->attach($player->id, [
                'group_id'      => $group->id,
                'grade'         => rand(6, 12),
                'shirt_size'    => 'M',
            ]);
        }

        // Seed inactive player
        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'inactive'      => Carbon::now()->toDateTimeString(),
            'group_id'      => $group->id,
            'grade'         => rand(6, 12),
            'shirt_size'    => 'YM',
        ]);
    }

    /**
     * Update the mailchimp ids so they match the staging list instead of production.
     */
    private function updateMailchimpIds()
    {
        Role::where('name', Role::LEAGUE_COORDINATOR)->update([
            'mailchimp_interest_id' => '4548244911',
        ]);
        Role::where('name', Role::HEAD_COACH)->update([
            'mailchimp_interest_id' => 'cea4f8e0dd',
        ]);
        Role::where('name', Role::COACH)->update([
            'mailchimp_interest_id' => 'e11132acbf',
        ]);
        Role::where('name', Role::QUIZMASTER)->update([
            'mailchimp_interest_id' => 'e58faebc7c',
        ]);
        Role::where('name', Role::GUARDIAN)->update([
            'mailchimp_interest_id' => '295ac3a88c',
        ]);
    }
}
