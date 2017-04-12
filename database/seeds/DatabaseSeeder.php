<?php

use App\Address;
use App\Competition\Tournaments\Quizmasters\QuizzingPreferences;
use App\EventType;
use App\Group;
use App\Groups\GroupCreator;
use App\GroupType;
use App\Invitation;
use App\ParticipantType;
use App\Players\PlayerCreator;
use App\Program;
use App\Receipt;
use App\Role;
use App\Season;
use App\Team;
use App\TeamSet;
use App\Tournament;
use App\User;
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
        $tournament = $this->seedTournament($director);

        // associate head coach's tournament as a registered team with the tournament
        // under the Southeast group
        $receipt = Receipt::firstOrFail();
        $group = Group::where('name', '!=', self::GROUP_NAME)->firstOrFail();
        $teamSet = $this->seedTeamSet($group, 1);
        $teamSet->update([
            'tournament_id' => Tournament::firstOrFail()->id,
        ]);
        $teamSet->teams()->update([
            'receipt_id' => $receipt->id
        ]);
        $insertData = [];
        foreach ($group->players()->active($this->season)->get() as $player) {
            $insertData[] = [
                'tournament_id' => $tournament->id,
                'player_id'     => $player->id,
                'receipt_id'    => $receipt->id,
                'updated_at'    => Carbon::now()->toDateTimeString(),
                'created_at'    => Carbon::now()->toDateTimeString(),
            ];
        }
        DB::table('tournament_players')->insert($insertData);

        Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'email'         => null,
            'user_id'       => $director->id,
            'inviter_id'    => $headCoach->id,
            'group_id'      => 2,
        ]);

        self::$isSeeding = false;
    }

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
        $group = $groupCreator->create($BKuhlHeadCoach, [
            'name'                  => 'Southeast Christian Church',
            'group_type_id'         => GroupType::CHURCH,
            'program_id'            => Program::TEEN,
            'address_id'            => $address->id,
            'meeting_address_id'    => $address->id,
        ]);

        $guardian = seedGuardian([], [
            'latitude'  => '38.301815',
            'longitude' => '-85.597701',
        ]);

        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'group_id'      => $group->id,
            'paid'          => new Carbon(),
            'grade'         => rand(6, 12),
            'shirt_size'    => 'YS',
        ]);
        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'group_id'      => $group->id,
            'paid'          => new Carbon(),
            'grade'         => rand(6, 12),
            'shirt_size'    => 'YM',
        ]);
        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'group_id'      => $group->id,
            'paid'          => new Carbon(),
            'grade'         => rand(6, 12),
            'shirt_size'    => 'YM',
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

        $guardian = seedGuardian([], [
            'latitude'  => '38.301815',
            'longitude' => '-85.597701',
        ]);

        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'group_id'      => $group->id,
            'grade'         => rand(6, 12),
            'shirt_size'    => 'YS',

            // needed for outstanding registration fee reminder
            'created_at'    => Carbon::now()->subWeeks('10')->toDateTimeString(),
        ]);

        $player = seedPlayer($guardian);
        $this->season->players()->attach($player->id, [
            'group_id'      => $group->id,
            'grade'         => rand(6, 12),
            'shirt_size'    => 'YM',

            // needed for outstanding registration fee reminder
            'created_at'    => Carbon::now()->subWeeks('10')->toDateTimeString(),
        ]);

        // Seed inactive player
        $player = seedPlayer($guardian);
        $player->update([
            'last_name'  => 'Inactive',
            'first_name' => 'Joe',
        ]);
        $this->season->players()->attach($player->id, [
            'inactive'      => Carbon::now()->toDateTimeString(),
            'group_id'      => $group->id,
            'grade'         => rand(6, 12),
            'shirt_size'    => 'M',
        ]);

        return $group;
    }

    private function seedTeamSet(Group $group, $maxTeams = 6) : TeamSet
    {
        $teamSet = TeamSet::create([
            'group_id'      => $group->id,
            'season_id'     => $this->season->id,
            'name'          => 'League Teams',
        ]);
        $players = $group->players()->active($this->season)->get();
        for ($x = 0; $x < $maxTeams; $x++) {
            $team = Team::create([
                'team_set_id'   => $teamSet->id,
                'name'          => 'Team '.($x+1),
            ]);

            if ($x === 0) {
                foreach ($players->random($players->count() < 6 ? $players->count() : 3) as $idx => $player) {
                    if (is_object($player)) {
                        $team->players()->attach($player->id, [
                            'order' => $idx + 1,
                        ]);
                    }
                }
            }
        }

        return $teamSet;
    }

    private function seedTournament($director) : Tournament
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
        $tournament->addCoordinator($director);
        $tournament->events()->create([
            'event_type_id'         => EventType::ROUND_ROBIN,
        ]);
        $tournament->events()->create([
            'event_type_id'         => EventType::DOUBLE_ELIMINATION,
        ]);
        $tournament->events()->create([
            'event_type_id'         => EventType::QUOTE_BEE,
            'price_per_participant' => '10.00',
        ]);
        $tournament->events()->create([
            'event_type_id'         => EventType::BUZZ_OFF,
        ]);
        $tournament->events()->create([
            'event_type_id'         => EventType::WRITTEN_TEST,
            'required'              => true,
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::PLAYER,
            'requires_registration' => 1,
            'fee'                   => '13.00',
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
            'fee'                   => '12.00',
            'onsite_fee'            => '40.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::ADULT,
            'requires_registration' => 1,
            'fee'                   => '30.00',
            'onsite_fee'            => '43.00',
        ]);
        $tournament->participantFees()->create([
            'participant_type_id'   => ParticipantType::FAMILY,
            'requires_registration' => 1,
            'fee'                   => '60.00',
            'onsite_fee'            => '75.00',
        ]);

        $group = Group::findOrFail(2);
        $tournament->tournamentQuizmasters()->create([
            'group_id'      => $group->id,
            'registered_by' => $group->owner_id,
            'first_name'    => 'Keith',
            'last_name'     => 'Webb',
            'email'         => 'kwebb@domain.com',
            'gender'        => 'M',
            'shirt_size'    => 'XL',
        ]);

        $user = User::where('email', self::QUIZMASTER_EMAIL)->first();
        $receipt = $this->seedReceipt($user);
        $quizzingPreferences = new QuizzingPreferences();
        $quizzingPreferences->setGamesQuizzedThisSeason(rand(1, 10));
        $quizzingPreferences->setQuizzingFrequency(rand(1, 5));
        $quizzingPreferences->setQuizzedAtThisTournamentBefore(true);
        $quizzingPreferences->setTimesQuizzedAtThisTournament(10);
        $tournament->tournamentQuizmasters()->create([
            'group_id'              => $group->id,
            'receipt_id'            => $receipt->id,
            'first_name'            => 'Warner',
            'last_name'             => 'Jackson',
            'email'                 => 'wjackson@domain.com',
            'quizzing_preferences'  => $quizzingPreferences,
            'gender'                => 'F',
            'phone'                 => '15553423434',
            'shirt_size'            => 'M',
        ]);

        // guest spectators
        $director = User::where('email', self::DIRECTOR_EMAIL)->first();
        $tournament->spectators()->create([
            'group_id'      => $group->id,
            'registered_by' => $group->owner_id,
            'receipt_id'    => $receipt->id,
            'first_name'    => 'Sarah',
            'last_name'     => 'Jones',
            'shirt_size'    => 'L',
            'phone'         => '555123'.rand(1234, 9999),
            'email'         => 'sjones@domain.com',
            'gender'        => 'F',
            'address_id'    => $director->primary_address_id,
        ]);
        $tournament->spectators()->create([
            'group_id'      => $group->id,
            'registered_by' => $group->owner_id,
            'first_name'    => 'Jonathan',
            'last_name'     => 'Wicker',
            'shirt_size'    => 'L',
            'phone'         => '555123'.rand(1234, 9999),
            'email'         => 'jwicker@domain.com',
            'gender'        => 'M',
            'address_id'    => $director->primary_address_id,
        ]);

        // family spectators
        $tournament->spectators()->create([
            'group_id'          => $group->id,
            'user_id'           => $director->id,
            'receipt_id'        => $receipt->id,
            'spouse_first_name' => 'Michelle',
            'spouse_gender'     => 'F',
            'spouse_shirt_size' => 'M',
            'phone'             => '555123'.rand(1234, 9999),
        ]);
        $spectator = $tournament->spectators()->create([
            'group_id'          => $group->id,
            'registered_by'     => $group->owner_id,
            'first_name'        => 'Clark',
            'last_name'         => 'Larkson',
            'shirt_size'        => 'XL',
            'phone'             => '555123'.rand(1234, 9999),
            'email'             => 'clarkson@domain.com',
            'gender'            => 'M',
            'spouse_first_name' => 'Lucy',
            'spouse_gender'     => 'F',
            'spouse_shirt_size' => 'M',
            'address_id'        => $director->primary_address_id,
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

        // invitations
        $tournament->invitations()->create([
            'status'        => Invitation::SENT,
            'type'          => Invitation::TYPE_MANAGE_TOURNAMENTS,
            'inviter_id'    => $group->owner_id,
            'user_id'       => User::where('email', AcceptanceTestingSeeder::GUARDIAN_EMAIL)->firstOrFail()->id,
        ]);
        $tournament->addCoordinator(User::where('email', self::HEAD_COACH_EMAIL)->firstOrFail());

        return $tournament;
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
