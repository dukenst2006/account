<?php

use App\Group;
use App\Receipt;
use App\TeamSet;
use App\Tournament;
use Carbon\Carbon;

class TournamentTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @var Tournament */
    protected $tournament;

    public function setUp()
    {
        parent::setUp();

        $this->tournament = Tournament::firstOrFail();

        $teamSet = TeamSet::firstOrFail();
        $teamSet->update([
            'tournament_id' => $this->tournament->id,
        ]);
    }

    /** @test */
    public function tournamentsWithFeesExcludeTeamsWithoutEnoughPlayers()
    {
        $this->assertEquals(1, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsWithFeesIncludeTeamsWithEnoughPlayers()
    {
        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->update([
            'receipt_id' => Receipt::firstOrFail()->id,
        ]);
        $playerIds = [1, 2, 3, 4];
        $firstTeam->players()->sync($playerIds);
        $this->markPlayersAsPaid($playerIds);

        $this->assertEquals(2, $this->tournament->eligibleTeams()->count());

        // assert a receipt is required
        DB::update('UPDATE tournament_players SET receipt_id = null');
        $this->assertEquals(0, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsWithFeesExcludeTeamsWithTooManyPlayers()
    {
        $firstTeam = $this->tournament->teams()->first();
        $playerIds = [1, 2, 3, 4];
        $firstTeam->players()->sync($playerIds);
        $this->markPlayersAsPaid($playerIds);

        $this->assertEquals(1, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsWithoutFeesExcludeTeamsWithoutEnoughPlayers()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $this->assertEquals(1, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsWithoutFeesIncludeTeamsWithEnoughPlayers()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->players()->sync([1, 2, 3, 4]);

        $this->assertEquals(2, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsWithoutFeesExcludeTeamsWithTooManyPlayers()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->players()->sync([1, 2, 3, 4, 5, 6, 7]);

        $this->assertEquals(1, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsOnlyIncludeTeamsWithEnoughQuizmastersForTheGroup()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $settings = $this->tournament->settings;
        $settings->requireQuizmasters('group');
        $settings->setQuizmastersToRequireByGroup(4);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        // make sure the team has enough players
        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->players()->sync([1, 2, 3, 4]);

        // exclude teams without enough quizmasters
        $this->assertEquals(0, $this->tournament->eligibleTeams()->count());

        // ensure if they have enough quizmasters they're included
        $settings->setQuizmastersToRequireByGroup(2);
        $this->tournament->update([
            'settings' => $settings,
        ]);
        $this->assertEquals(1, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsOnlyIncludeTeamsWithEnoughQuizmastersForTheirTeams()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $settings = $this->tournament->settings;
        $settings->requireQuizmasters('team_count');
        $settings->setQuizmastersToRequireByTeamCount(3);
        $settings->setTeamCountToRequireQuizmastersBy(1);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->players()->sync([1, 2, 3, 4]);

        $this->assertEquals(0, $this->tournament->eligibleTeams()->count());

        // ensure if they have enough quizmasters they're included
        $settings->setQuizmastersToRequireByTeamCount(2);
        $this->tournament->update([
            'settings' => $settings,
        ]);
        $this->assertEquals(1, $this->tournament->eligibleTeams()->count());
    }

    /** @test */
    public function tournamentsWithFeesExcludePlayersOnTeamsWithoutEnoughPlayers()
    {
        $this->assertEquals(3, $this->tournament->eligiblePlayers()->count());
    }

    /** @test */
    public function tournamentsWithFeesIncludePlayersOnTeamsWithEnoughPlayers()
    {
        $firstTeam = $this->tournament->teams()->first();
        $playerIds = [1, 2, 3, 4];
        $firstTeam->players()->sync($playerIds);
        $this->markPlayersAsPaid($playerIds);

        $this->assertEquals(7, $this->tournament->eligiblePlayers()->count());

        // assert a receipt is required
        DB::update('UPDATE tournament_players SET receipt_id = null');
        $this->assertEquals(0, $this->tournament->eligiblePlayers()->count());
    }

    /** @test */
    public function tournamentsWithFeesIncludePlayersOnTeamsWithTooManyPlayers()
    {
        $firstTeam = $this->tournament->teams()->first();
        $playerIds = [1, 2, 3, 4];
        $firstTeam->players()->sync($playerIds);
        $this->markPlayersAsPaid($playerIds);

        // an assumption is made here that if a tournament has too many players they
        // will never be able to be paid for
        $this->assertEquals(7, $this->tournament->eligiblePlayers()->count());
    }

    /** @test */
    public function tournamentsWithoutFeesExcludePlayersOnTeamsWithTooManyPlayers()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->players()->sync([1, 2, 3, 4, 5, 6, 7]);

        $this->assertEquals(3, $this->tournament->eligiblePlayers()->count());
    }

    /** @test */
    public function tournamentsWithoutFeesExcludePlayersOnTeamsWithoutEnoughPlayers()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $this->assertEquals(3, $this->tournament->eligiblePlayers()->count());
    }

    /** @test */
    public function tournamentsWithoutFeesIncludePlayersOnTeamsWithEnoughPlayers()
    {
        // remove all fees
        $this->tournament->participantFees()->update([
            'fee'               => null,
            'onsite_fee'        => null,
            'earlybird_fee'     => null,
        ]);

        $firstTeam = $this->tournament->teams()->first();
        $firstTeam->players()->sync([1, 2, 3, 4]);

        $this->assertEquals(7, $this->tournament->eligiblePlayers()->count());
    }

    /** @test */
    public function requiredQuizmasterCountIsBasedOffOfTeams()
    {
        $group = Mockery::mock(Group::class);
        $group->shouldReceive('getAttribute')->andReturn($group);

        $tournament = Tournament::firstOrFail();
        $settings = $tournament->settings;
        $settings->setQuizmastersToRequireByTeamCount(2);
        $settings->setTeamCountToRequireQuizmastersBy(4);
        $tournament->update([
            'settings' => $settings,
        ]);

        $this->assertEquals(2, $tournament->numberOfQuizmastersRequiredByTeamCount(3));
        $this->assertEquals(2, $tournament->numberOfQuizmastersRequiredByTeamCount(4));
        $this->assertEquals(4, $tournament->numberOfQuizmastersRequiredByTeamCount(8));
        $this->assertEquals(4, $tournament->numberOfQuizmastersRequiredByTeamCount(9));
    }

    /** @test */
    public function registrationEndsAtEndOfTheDay()
    {
        $this->tournament->registration_end = Carbon::createFromTime(23, 59, 59, 'America/New_York');

        $this->assertTrue($this->tournament->isRegistrationOpen());
    }

    protected function markPlayersAsPaid(array $playerIds)
    {
        $receipt = Receipt::firstOrFail();
        $insertData = [];
        foreach ($playerIds as $playerId) {
            $insertData[] = [
                'tournament_id' => $this->tournament->id,
                'player_id'     => $playerId,
                'receipt_id'    => $receipt->id,
            ];
        }
        DB::table('tournament_players')->insert($insertData);
    }
}
