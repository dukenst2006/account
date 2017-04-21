<?php

use App\ParticipantType;
use App\Receipt;
use App\Spectator;
use App\TeamSet;
use App\Tournament;
use App\TournamentQuizmaster;
use App\User;
use Carbon\Carbon;

class GroupRegistrationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsHeadCoach;
    use \Helpers\SimulatesTransactions;

    /** @var Tournament */
    protected $tournament;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();

        $this->tournament = Tournament::firstOrFail();
    }

    /** @test */
    public function canViewGroupRegistrationStatus()
    {
        $this
            ->visit('/tournaments/'.$this->tournament->slug)
            ->click('#register-group')
            ->see('Quizmasters');
    }

    /** @test */
    public function canInviteQuizmasters()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $phone = time();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Quizmaster')
            ->type('John', 'first_name')
            ->type('Gutson', 'last_name')
            ->type('tester123'.time().'@no-domain.com', 'email')
            ->type($phone, 'phone')
            ->see('Save & Add Another')
            ->press('Save')
            ->see('Quizmaster has been added')
            ->see('John Gutson');

        $spectator = TournamentQuizmaster::orderBy('id', 'DESC')->firstOrFail();
        $this->assertEquals($this->headCoach()->id, $spectator->registered_by);
        $this->assertEquals($phone, $spectator->phone);
    }

    /** @test */
    public function cantInviteQuizmasterWhoIsAlreadyRegistered()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $quizmaster = TournamentQuizmaster::firstOrFail();

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Quizmaster')
            ->type('John', 'first_name')
            ->type('Gutson', 'last_name')
            ->type($quizmaster->email, 'email')
            ->type(time(), 'phone')
            ->press('Save')
            ->see('This quizmaster is already registered for this tournament');
    }

    /** @test */
    public function cantInviteQuizmasterWhoIsUserAndAlreadyRegistered()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $user = User::where('email', AcceptanceTestingSeeder::GUARDIAN_EMAIL)->firstOrFail();
        TournamentQuizmaster::firstOrFail()->update([
            'user_id'   => $user->id,
            'email'     => null,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Quizmaster')
            ->type('John', 'first_name')
            ->type('Gutson', 'last_name')
            ->type($user->email, 'email')
            ->type(time(), 'phone')
            ->press('Save')
            ->see('This quizmaster is already registered for this tournament');
    }

    /** @test */
    public function cantRegisterAsGuest()
    {
        // since only the guardian login is seeded, we can tear down
        // to "log out"
        $this->tearDown();
        parent::setUp();

        $this
            ->visit('/tournaments/'.$this->tournament->slug)
            ->click('#register-group')

            // if we don't go anywhere, the tooltip was hopefully shown
            ->seePageIs('/tournaments/'.$this->tournament->slug);
    }

    /** @test */
    public function quizmasterCanProvideQuizzingPreferences()
    {
        $tournamentQuizmaster = TournamentQuizmaster::firstOrFail();
        $shirtSize = 'XL';
        $gamesQuizzed = 'Fewer than 15';
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/registration/quizmaster-preferences/'.$tournamentQuizmaster->guid)
            ->select($gamesQuizzed, 'games_quizzed_this_season')
            ->select($shirtSize, 'shirt_size')
            ->press('Save')
            ->see('Your quizzing preferences have been updated');

        $tournamentQuizmaster = TournamentQuizmaster::firstOrFail();
        $this->assertEquals($shirtSize, $tournamentQuizmaster->shirt_size);
        $this->assertEquals($gamesQuizzed, $tournamentQuizmaster->quizzing_preferences->gamesQuizzedThisSeason());
    }

    /** @test */
    public function headCoachCanRegisterAsSpectator()
    {
        $this->bypassInitialRegistrationInstructions();

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Adult/Family');

        // verify asked if I want to register myself
        $this->see('planning to attend this tournament you');

        $this->tournament->spectators()->update([
            'user_id' => null,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Adult/Family')
            ->check('registering_as_current_user')
            ->press('Save')
            ->see('Adult has been added')
            ->see($this->headCoach()->full_name);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Adult/Family')
            ->dontSee('planning to attend this tournament you');
    }

    /** @test */
    public function canRegisterSpectator()
    {
        $this->bypassInitialRegistrationInstructions();

        $firstName = time();
        $lastName = microtime();
        $phone = time();

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Add Adult/Family')
            ->press('Save')
            ->see('The first name field is required')
            ->see('The email field is required')
            ->see('The street address field is required')
            ->see('The zip code field is required')

            ->type($firstName, 'first_name')
            ->type($lastName, 'last_name')
            ->type($phone, 'phone')
            ->type('asdf@asdf.com', 'email')
            ->type('123 Test Street', 'address_one')
            ->type('40241', 'zip_code')

            ->press('Save')
            ->see('Adult has been added')
            ->see($this->headCoach()->full_name);

        $spectator = Spectator::orderBy('id', 'DESC')->firstOrFail();
        $this->assertEquals($this->headCoach()->id, $spectator->registered_by);
        $this->assertEquals($phone, $spectator->phone);
    }

    /** @test */
    public function cantModifyRegistrationWhenRegistrationClosed()
    {
        $this->tournament->update([
            'registration_start'    => Carbon::now()->subDays(10)->format('m/d/Y'),
            'registration_end'      => Carbon::now()->subDays(1)->format('m/d/Y'),
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')

            // can't add players to optional events
            ->dontSee('Manage Participation')

            // can't manage teams
            ->dontSee('Manage Teams')

            // can't pay additional fees
            // this verifies spectators, quizmasters, etc.
            ->dontSee('Pay Fees')

            // can't add spectators
            ->dontSee('Add Adult/Family')

            // can't add quizmasters
            ->dontSee('Add Quizmaster')

            ->see('Online registration for this tournament is now closed');
    }

    /** @test */
    public function canDuplicateTeams()
    {
        $teamSet = TeamSet::firstOrFail();
        $duplicater = new \App\Competition\Teams\Duplicater();
        $newTeamSet = $duplicater->duplicate($teamSet, [
            'tournament_id', 'receipt_id',
        ]);

        $this->assertNotEquals($teamSet->id, $newTeamSet->id);
        $this->assertNull($newTeamSet->receipt_id);
        $this->assertNull($newTeamSet->tournament_id);
        $this->assertEquals($teamSet->teams()->count(), $newTeamSet->teams()->count());
        foreach ($teamSet->teams as $key => $team) {
            $this->assertEquals($team->players()->count(), $newTeamSet->teams->get($key)->players()->count());
        }
    }

    /** @test */
    public function canSetTeams()
    {
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')

            ->click('Register Teams')
            ->click('Use these teams')
            ->seePageIs('/tournaments/'.$this->tournament->slug.'/registration/group/events')

            ->visit('/tournaments/'.$this->tournament->slug.'/group')

            // assert we're indicating some teams aren't yet paid for
            ->see('6 require payment')
            ->see('2 require payment');
    }

    /** @test */
    public function dontSeeQuizmasterAndSpectatorsWhenThereAreNotAny()
    {
        $this->tournament->tournamentQuizmasters()->delete();
        DB::statement('DELETE FROM tournament_spectator_minors WHERE id > 0');
        $this->tournament->spectators()->delete();

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')

            ->dontSee('Add Adult/Family')
            ->dontSee('Add Spectator')

            ->see('Register Teams');
    }

    /** @test */
    public function createsNewTeams()
    {
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/registration/group/choose-teams')
            ->click('Create new teams');

        $teamSet = $this->group()->teamSets()->orderBy('id', 'DESC')->first();
        $this->assertEquals($this->tournament->name.' Teams', $teamSet->name);

        $this->seePageIs('/teamsets/'.$teamSet->id);
    }

    /** @test */
    public function createsNewTeamsIfNoExistingTeamsToChooseFrom()
    {
        $this->group()->teamSets()->update([
            'group_id' => ($this->group()->id - 1),
        ]);

        $this->visit('/tournaments/'.$this->tournament->slug.'/registration/group/choose-teams');

        $teamSet = $this->group()->teamSets()->first();
        $this->assertEquals($this->tournament->name.' Teams', $teamSet->name);

        $this->seePageIs('/teamsets/'.$teamSet->id);
    }

    /** @test */
    public function canSignupPlayersForOptionalEvents()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $players = $teamSet->players()->get();
        $event = $this->tournament->individualEvents()->first();

        // assertions can't be trusted if we don't start with no players
        $this->assertEquals(0, $event->players()->count());

        // attach one of the players to an event so we can assert the
        // receipt_id isn't overwritten
        $receipt = Receipt::firstOrFail();
        $event->players()->attach([
            $players->first()->id => [
                'receipt_id' => $receipt->id,
            ],
        ]);

        $this->assertEquals(1, $event->players()->count());
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Manage Participation')
            ->check('event['.$event->id.']['.$players->first()->id.']')
            ->check('event['.$event->id.']['.$players->get(1)->id.']')
            ->press('Save & Continue')
            ->seePageIs('/tournaments/'.$this->tournament->slug.'/group')

            // verify we're showing payment is required for a participant
            ->see('1 require payment');

        $this->assertEquals(2, $event->players()->count());

        // verify previously set receipt_id isn't overwritten
        $this->assertEquals($receipt->id, $event->players->get(1)->pivot->receipt_id);
    }

    /** @test */
    public function rejectPayingFeesWhenTeamsDontMeetPlayerCount()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $this->skipErrorAboutPlayersStillRequiringSeasonalFees();

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')
            ->see('6 team(s) must be updated to have between 3-6 players before you can submit payment.');
    }

    /** @test */
    public function canPayGroupRegistration()
    {
        $this->simulateTransaction();
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $this->skipErrorAboutPlayersStillRequiringSeasonalFees();

        $players = $teamSet->players()->get();
        $event = $this->tournament->individualEvents()->requiringFees()->first();

        $settings = $this->tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $event->players()->attach([
            $players->first()->id,
        ]);

        $this->assertEquals(1, $event->players()->count());
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')

            ->see('Team Tournament Registration (Early Bird)')
            ->see('$'.$this->tournament->fee(ParticipantType::TEAM))

            ->see('Player Tournament Registration')
            ->see('$'.$this->tournament->fee(ParticipantType::PLAYER))

            ->see('Quizmaster Tournament Registration')
            ->see('$'.$this->tournament->fee(ParticipantType::QUIZMASTER))

            ->see('Adult Tournament Registration')
            ->see('$'.$this->tournament->fee(ParticipantType::ADULT))

            ->see('Family Tournament Registration')
            ->see('$'.$this->tournament->fee(ParticipantType::FAMILY))

            ->see('Player Quote Bee Registration')
            ->see('$10.00')

            ->press('Submit')

            ->seePageIs('/tournaments/'.$this->tournament->slug.'/group')
            ->see('Your registration is complete');
    }

    /** @test */
    public function payingRegistrationExcludesTeamsWhenTeamCountIsMaxed()
    {
        $this->tournament->update([
            'max_teams' => 0,
        ]);
        $this->bypassInitialRegistrationInstructions();

        $this->skipErrorAboutPlayersStillRequiringSeasonalFees();

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')
            ->dontSee('Team Tournament')
            ->see('Reduce your number of teams and try again');
    }

    /** @test */
    public function promptsForFeesWhenOnlyEventsHaveOutstandingFees()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->dontSee('Portions of your registration require payment before they are complete.');

        // attach a player to an event
        $players = $teamSet->players()->get();
        $event = $this->tournament->individualEvents()->requiringFees()->first();
        $event->players()->attach([
            $players->first()->id,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see('Portions of your registration require payment before they are complete.');
    }

    /** @test */
    public function canDeleteQuizmastersWhenThereAreNoFees()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $quizmaster = $teamSet->tournament->tournamentQuizmasters()->first();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see($quizmaster->full_name)
            ->press('delete-quizmaster-'.$quizmaster->id)
            ->see($quizmaster->full_name.' has been removed');
    }

    /** @test */
    public function canDeleteQuizmastersWhenThereAreFees()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // make sure there's a fee
        $this->tournament->participantFees()->where('participant_type_id', ParticipantType::QUIZMASTER)->update([
            'fee' => 5,
        ]);

        $quizmaster = $teamSet->tournament->tournamentQuizmasters()->unpaid()->first();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see($quizmaster->full_name)
            ->press('delete-quizmaster-'.$quizmaster->id)
            ->see($quizmaster->full_name.' has been removed');
    }

    /** @test */
    public function canDeleteSpectatorsWhenThereAreNoFees()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $spectator = $teamSet->tournament->spectators()->first();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see($spectator->full_name)
            ->press('delete-spectator-'.$spectator->id)
            ->see($spectator->full_name.' has been removed');
    }

    /** @test */
    public function canDeleteSpectatorsWhenThereAreFees()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // make sure there's a fee
        $this->tournament->participantFees()->where('participant_type_id', ParticipantType::QUIZMASTER)->update([
            'fee' => 5,
        ]);

        $spectator = $teamSet->tournament->spectators()->unpaid()->first();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see($spectator->full_name)
            ->press('delete-spectator-'.$spectator->id)
            ->see($spectator->full_name.' has been removed');
    }

    /** @test */
    public function doesntAllowRegistrationPaymentWhenQuizmasterCriteriaIsntMetForGroups()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $settings = $this->tournament->settings;
        $settings->requireQuizmasters('group');
        $settings->setQuizmastersToRequireByGroup(2);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')
            ->dontSee('You need to register 2 quizmaster(s) before you can proceed.');
    }

    /** @test */
    public function doesntAllowRegistrationPaymentWhenQuizmasterCriteriaIsntMetByGroup()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $this->skipErrorAboutPlayersStillRequiringSeasonalFees();

        $settings = $this->tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);

        $settings->requireQuizmasters('group');
        $settings->setQuizmastersToRequireByGroup(10);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')
            ->see('You need to register 10 quizmaster(s) before you can proceed.');
    }

    /** @test */
    public function doesntAllowRegistrationPaymentWhenQuizmasterCriteriaIsntMetForTeamCount()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        $this->skipErrorAboutPlayersStillRequiringSeasonalFees();

        $settings = $this->tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);

        $settings->requireQuizmasters('team_count');
        $settings->setQuizmastersToRequireByTeamCount(2);
        $settings->setTeamCountToRequireQuizmastersBy(1);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')
            ->see('Because you have 6 team(s), you need 12 quizmaster(s) before you can proceed.');
    }

    /** @test */
    public function showsIneligibleWarningWhenQuizmasterCriteriaIsntMetByGroup()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // eliminate fees so we don't need to seed players as paid
        $this->tournament->participantFees()->where('participant_type_id', ParticipantType::QUIZMASTER)->update([
            'fee' => null,
        ]);

        $settings = $this->tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);

        $settings->requireQuizmasters('group');
        $settings->setQuizmastersToRequireByGroup(10);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see('You need to register '.$this->tournament->settings->quizmastersToRequireByGroup().' quizmaster(s) before your registration is complete.');
    }

    /** @test */
    public function showsIneligibleWarningWhenQuizmasterCriteriaIsntMetByTeamCount()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        // eliminate fees so we don't need to seed players as paid
        $this->tournament->participantFees()->where('participant_type_id', ParticipantType::QUIZMASTER)->update([
            'fee' => null,
        ]);

        $settings = $this->tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);

        $settings->requireQuizmasters('team_count');
        $settings->setQuizmastersToRequireByTeamCount(4);
        $settings->setTeamCountToRequireQuizmastersBy(1);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $teamCount = $this->tournament->teamSet($this->group())->teams()->count();
        $numberOfQuizmastersRequired = $this->tournament->numberOfQuizmastersRequiredByTeamCount($teamCount);
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->see('Because you have '.$teamCount.' team(s), you need '.$numberOfQuizmastersRequired.' quizmaster(s) before your registration is complete.');
    }

    /** @test */
    public function rejectsPlayersWithOutstandingFees()
    {
        $teamSet = $this->bypassInitialRegistrationInstructions();

        DB::update('UPDATE player_season SET paid = NULL');

        $settings = $this->tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $playersWithUnpaidSeasonalFees = $this->tournament->teamSet($this->group())->players()->pendingRegistrationPayment($this->tournament->season)->get();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/group')
            ->click('Pay Fees')
            ->see('The following player(s) still have outstanding seasonal registration fees: '.implode(',', $playersWithUnpaidSeasonalFees->pluck('full_name')->toArray()));
    }

    private function bypassInitialRegistrationInstructions() : TeamSet
    {
        $teamSet = TeamSet::firstOrFail();
        $teamSet->update([
            'tournament_id' => $this->tournament->id,
        ]);

        return $teamSet;
    }

    private function skipErrorAboutPlayersStillRequiringSeasonalFees()
    {
        DB::update('UPDATE player_season SET paid = NOW()');
    }
}
