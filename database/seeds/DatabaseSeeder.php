<?php

use BibleBowl\Address;
use BibleBowl\EventType;
use BibleBowl\Group;
use BibleBowl\Groups\GroupCreator;
use BibleBowl\GroupType;
use BibleBowl\Invitation;
use BibleBowl\ParticipantType;
use BibleBowl\Players\PlayerCreator;
use BibleBowl\Program;
use BibleBowl\Receipt;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\Team;
use BibleBowl\TeamSet;
use BibleBowl\Tournament;
use BibleBowl\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private static $isSeeding = false;

    /** @var User */
    public static $guardian;

    const GROUP_NAME = 'Mount Pleasant Christian Church';
    const DIRECTOR_EMAIL = 'benkuhl+admin@gmail.com';
    const HEAD_COACH_EMAIL = 'benkuhl+headcoach@gmail.com';
    const GUARDIAN_EMAIL = 'benkuhl+guardian@gmail.com';
    const QUIZMASTER_EMAIL = 'benkuhl+quizmaster@gmail.com';

    /** @var Season */
    private $season;

    /** @var \Faker\Generator */
    private $faker;

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
        $this->faker = Factory::create();

        // load ModelFactory.php so functions can be used later
        factory(User::class);

        Season::create([
            'name' => (date('Y') - 1).'-'.date('y'),
        ]);
        $this->call('ProductionSeeder');

        self::$isSeeding = true;

        $this->season = Season::orderBy('id', 'DESC')->first();

        $director = $this->seedAdmin();
        $this->seedGuardian();
        $this->seedQuizmaster();
        $headCoach = $this->seedHeadCoach();

        $this->call('AcceptanceTestingSeeder');

        if (app()->environment('staging')) {
            $this->call('StagingSeeder');
        }

        $this->seedTournament($director);

        Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'email'         => null,
            'user_id'       => $director->id,
            'inviter_id'    => $headCoach->id,
            'group_id'      => 2,
        ]);

        self::$isSeeding = false;
    }

    /**
     * @return User
     */
    private function seedAdmin()
    {
        $address = Address::create([
            'name'             => 'Home',
            'address_one'      => '11025 Eagles Cove Dr.',
            'address_two'      => null,
            'latitude'         => '38.2515659',
            'longitude'        => '-85.615241',
            'city'             => 'Louisville',
            'state'            => 'KY',
            'zip_code'         => '40241',
        ]);
        $director = User::create([
            'status'               => User::STATUS_CONFIRMED,
            'first_name'           => 'Ben',
            'last_name'            => 'Director',
            'email'                => self::DIRECTOR_EMAIL,
            'password'             => bcrypt('changeme'),
            'primary_address_id'   => $address->id,
        ]);
        $director->addresses()->save($address);

        $role = Role::where('name', Role::ADMIN)->firstOrFail();
        $director->assign($role);

        return $director;
    }

    private function seedHeadCoach()
    {
        $address = Address::create([
            'name'             => 'Home',
            'address_one'      => '11025 Eagles Cove Dr.',
            'address_two'      => null,
            'latitude'         => '38.2515659',
            'longitude'        => '-85.615241',
            'city'             => 'Louisville',
            'state'            => 'KY',
            'zip_code'         => '40241',
        ]);
        $BKuhlHeadCoach = User::create([
          'status'                => User::STATUS_CONFIRMED,
          'first_name'            => 'Ben',
          'last_name'             => 'HeadCoach',
          'email'                 => self::HEAD_COACH_EMAIL,
          'password'              => bcrypt('changeme'),
          'primary_address_id'    => $address->id,
        ]);
        $BKuhlHeadCoach->addresses()->save($address);

        /** @var GroupCreator $groupCreator */
        $groupCreator = App::make(GroupCreator::class);
        $groupCreator->create($BKuhlHeadCoach, [
            'name'                  => 'Southeast Christian Church',
            'group_type_id'         => GroupType::CHURCH,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id,
        ]);

        $address = factory(Address::class)->create([
            'name'      => 'Church',
            'latitude'  => '38.316334',
            'longitude' => '-85.573143',
        ]);
        $BKuhlHeadCoach->addresses()->save($address);
        $BKuhlHeadCoach = User::findOrFail($BKuhlHeadCoach->id);
        $group = $this->seedGroupWithPlayers($groupCreator, $BKuhlHeadCoach, $address);
        $this->seedTeamSet($group);

        return $BKuhlHeadCoach;
    }

    private function seedGuardian()
    {
        $shirtSizes = ['S', 'YS', 'M', 'L', 'YL', 'YM'];
        $addresses = ['Home', 'Work', 'Church', 'Vacation Home'];
        $savedAddresses = [];
        foreach ($addresses as $key => $name) {
            $savedAddresses[] = factory(Address::class)->create([
                'name' => $name,
            ]);
        }

        self::$guardian = User::create([
            'status'                   => User::STATUS_CONFIRMED,
            'first_name'               => 'Ben',
            'last_name'                => 'Guardian',
            'email'                    => self::GUARDIAN_EMAIL,
            'phone'                    => '5553546789',
            'password'                 => bcrypt('changeme'),
            'primary_address_id'       => $savedAddresses[0]->id,
        ]);
        self::$guardian->addresses()->saveMany($savedAddresses);

        // Generate fake player information.
        $playerCreator = App::make(PlayerCreator::class);
        $playerCreator->create(self::$guardian, [
            'first_name'    => 'David',
            'last_name'     => 'Webb',
            'gender'        => 'M',
            'birthday'      => $this->faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y'),
        ]);

        self::$guardian = User::findOrFail(self::$guardian->id);
        $playerCreator->create(self::$guardian, [
            'first_name'    => 'Ethan',
            'last_name'     => 'Smith',
            'gender'        => 'M',
            'birthday'      => $this->faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y'),
        ]);

        $playerCreator->create(self::$guardian, [
            'first_name'    => 'Olivia',
            'last_name'     => 'Brown',
            'gender'        => 'F',
            'birthday'      => $this->faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y'),
        ]);

        $playerCreator->create(self::$guardian, [
            'first_name'    => 'Brad',
            'last_name'     => 'Anderson',
            'gender'        => 'M',
            'birthday'      => $this->faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y'),
        ]);
    }

    private function seedQuizmaster()
    {
        $addresses = ['Home', 'Work', 'Church', 'Vacation Home'];
        $savedAddresses = [];
        foreach ($addresses as $key => $name) {
            $savedAddresses[] = factory(Address::class)->create([
                'name' => $name,
            ]);
        }

        self::$guardian = User::create([
            'status'                   => User::STATUS_CONFIRMED,
            'first_name'               => 'Ben',
            'last_name'                => 'Quizmaster',
            'email'                    => self::QUIZMASTER_EMAIL,
            'phone'                    => '5553546789',
            'password'                 => bcrypt('changeme'),
            'primary_address_id'       => $savedAddresses[0]->id,
        ]);
        self::$guardian->addresses()->saveMany($savedAddresses);
    }

    private function seedGroupWithPlayers(GroupCreator $groupCreator, User $headCoach, Address $address)
    {
        $group = $groupCreator->create($headCoach, [
            'name'                  => self::GROUP_NAME,
            'group_type_id'         => GroupType::CHURCH,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id,
        ]);

        $shirtSizes = ['S', 'YS', 'M', 'L', 'YL', 'YM'];
        $guardian = seedGuardian([], [
            'latitude'  => '38.301815',
            'longitude' => '-85.597701',
        ]);

        for ($x = 0; $x <= 2; $x++) {
            $player = seedPlayer($guardian);
            $this->season->players()->attach($player->id, [
                'group_id'      => $group->id,
                'grade'         => rand(6, 12),
                'shirt_size'    => $shirtSizes[array_rand($shirtSizes)],

                // needed for outstanding registration fee reminder
                'created_at'    => Carbon::now()->subWeeks('10')->toDateTimeString(),
            ]);
        }

        // Seed inactive player
        $player = seedPlayer($guardian);
        $player->update([
            'last_name' => 'Inactive',
            'first_name' => 'Joe',
        ]);
        $this->season->players()->attach($player->id, [
            'inactive'      => Carbon::now()->toDateTimeString(),
            'group_id'      => $group->id,
            'grade'         => rand(6, 12),
            'shirt_size'    => $shirtSizes[array_rand($shirtSizes)],
        ]);

        return $group;
    }

    private function seedTeamSet(Group $group)
    {
        $teamSet = TeamSet::create([
            'group_id'      => $group->id,
            'season_id'     => $this->season->id,
            'name'          => 'League Teams',
        ]);
        $players = $group->players;
        for ($x = 1; $x <= 8; $x++) {
            $team = Team::create([
                'team_set_id'   => $teamSet->id,
                'name'          => 'Team '.$x,
            ]);

            $playerCount = ($x <= 3 ? $x - 1 : 0);
            if ($playerCount > 0) {
                foreach ($players->random($playerCount) as $idx => $player) {
                    if (is_object($player)) {
                        $team->players()->attach($player->id, [
                            'order' => $idx + 1,
                        ]);
                    }
                }
            }
        }
    }

    private function seedTournament($director)
    {
        $tournamentName = 'My Test Tournament';
        $tournament = Tournament::create([
            'program_id'            => Program::TEEN,
            'slug'                  => $this->season->name.' '.$tournamentName,
            'season_id'             => $this->season->id,
            'name'                  => $tournamentName,
            'start'                 => Carbon::now()->addMonths(5)->format('m/d/Y'),
            'end'                   => Carbon::now()->addMonths(7)->format('m/d/Y'),
            'registration_start'    => Carbon::now()->subMonths(3)->format('m/d/Y'),
            'registration_end'      => Carbon::now()->addDays(4)->format('m/d/Y'),
            'creator_id'            => $director->id,
            'details'               => '<h3>Nearby Hotels</h3><p>There are a few nearby:</p><ul><li>Option #1</li></ul>',
            'max_teams'             => 64,
            'lock_teams'            => Carbon::now()->addMonths(3)->addWeeks(2)->format('m/d/Y'),
            'earlybird_ends'        => Carbon::now()->addMonths(3)->format('m/d/Y'),
        ]);
        $tournament->events()->create([
            'event_type_id'         => EventType::ROUND_ROBIN,
            'price_per_participant' => '25.00',
        ]);
        $tournament->events()->create([
            'event_type_id'         => EventType::DOUBLE_ELIMINATION,
            'price_per_participant' => '35.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'requires_registration' => 1,
            'fee'                   => '15.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::TEAM,
            'requires_registration' => 1,
            'earlybird_fee'         => '50.00',
            'fee'                   => '75.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::QUIZMASTER,
            'requires_registration' => 1,
            'fee'                   => '30.00',
            'onsite_fee'            => '40.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::ADULT,
            'requires_registration' => 1,
            'fee'                   => '30.00',
            'onsite_fee'            => '40.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::FAMILY,
            'requires_registration' => 1,
            'fee'                   => '60.00',
            'onsite_fee'            => '75.00',
        ]);

        $groupId = 2;
        $tournament->tournamentQuizmasters()->create([
            'group_id'      => $groupId,
            'first_name'    => 'Keith',
            'last_name'     => 'Webb',
            'email'         => 'kwebb@domain.com',
            'gender'        => 'M',
        ]);

        $user = User::where('email', self::QUIZMASTER_EMAIL)->first();
        $receipt = $this->seedReceipt($user);
        $tournament->tournamentQuizmasters()->create([
            'group_id'      => $groupId,
            'receipt_id'    => $receipt->id,
            'first_name'    => 'Warner',
            'last_name'     => 'Jackson',
            'email'         => 'wjackson@domain.com',
            'gender'        => 'F',
        ]);

        // guest spectators
        $director = User::where('email', self::DIRECTOR_EMAIL)->first();
        $tournament->spectators()->create([
            'group_id'      => $groupId,
            'receipt_id'    => $receipt->id,
            'first_name'    => 'Sarah',
            'last_name'     => 'Jones',
            'shirt_size'    => 'L',
            'email'         => 'sjones@domain.com',
            'gender'        => 'F',
            'address_id'    => $director->primary_address_id,
        ]);
        $tournament->spectators()->create([
            'group_id'      => $groupId,
            'first_name'    => 'Jonathan',
            'last_name'     => 'Wicker',
            'shirt_size'    => 'L',
            'email'         => 'jwicker@domain.com',
            'gender'        => 'M',
            'address_id'    => $director->primary_address_id,
        ]);

        // family spectators
        $tournament->spectators()->create([
            'group_id'          => $groupId,
            'user_id'           => $director->id,
            'receipt_id'        => $receipt->id,
            'spouse_first_name' => 'Michelle',
            'spouse_gender'     => 'F',
            'spouse_shirt_size' => 'M',
        ]);
        $spectator = $tournament->spectators()->create([
            'group_id'          => $groupId,
            'first_name'        => 'Clark',
            'last_name'         => 'Larkson',
            'shirt_size'        => 'XL',
            'email'             => 'clarkson@domain.com',
            'gender'            => 'M',
            'spouse_first_name' => 'Lucy',
            'spouse_gender'     => 'F',
            'spouse_shirt_size' => 'M',
        ]);
        $spectator->minors()->create([
            'name'          => 'Jonathan',
            'age'           => '6',
            'shirt_size'    => 'YS',
            'gender'        => 'M',
        ]);
        $spectator->minors()->create([
            'name'          => 'Christine',
            'age'           => '12',
            'shirt_size'    => 'YM',
            'gender'        => 'F',
        ]);
    }

    private function seedReceipt(User $user) : Receipt
    {
        $program = Program::firstOrFail();
        $receipt = Receipt::create([
            'total'                     => 15.00,
            'payment_reference_number'  => uniqid(),
            'first_name'                => $user->first_name,
            'last_name'                 => $user->last_name,
            'user_id'                   => $user->id,
            'address_id'                => $user->primary_address_id,
        ]);

        $receipt->items()->create([
            'sku'           => $program->sku,
            'description'   => $program->name.' Seasonal Registration',
            'quantity'      => '2',
            'price'         => $program->registration_fee,
        ]);

        return $receipt;
    }
}
