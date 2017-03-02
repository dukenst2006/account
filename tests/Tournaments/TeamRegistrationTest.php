<?php

use App\Group;
use App\Team;
use App\TeamSet;
use App\Tournament;
use App\Users\Auth\SessionManager;
use Carbon\Carbon;

class TeamRegistrationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsHeadCoach;

    /** @var Tournament */
    protected $tournament;

    /** @var TeamSet */
    protected $teamSet;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
        $this->withSession([
            SessionManager::GROUP   => Group::findOrFail(2)->toArray(),
        ]);

        $this->tournament = Tournament::firstOrFail();
        $this->teamSet = $this->group()->teamSets()->first();

        // assign teams to tournament
        $this->teamSet->update([
            'tournament_id' => $this->tournament->id,
        ]);

        // lock teams
        $this->tournament->update([
            'lock_teams' => Carbon::now()->subMinute(1)->format('m/d/Y'),
        ]);
    }

    /**
     * @test
     */
    public function teamsGetLocked()
    {
        $player = $this->group()->players()->notOnTeamSet($this->teamSet)->first();

        $this
            ->visit('/teamsets/'.$this->teamSet->id)

            // player roster isn't shown
            ->dontSee($player->full_name)

            // teams are locked
            ->dontSee('#add-team')
            ->see('Teams are locked and can no longer be modified');

        $this->assertNotFalse(strstr($this->response->original, 'teamsEditable = false'));
    }

    /**
     * @test
     */
    public function cannotUpdateTeamSetName()
    {
        $this
            ->patch('/teamsets/'.$this->teamSet->id, [
                'name' => uniqid(),
            ])
            ->assertResponseStatus(403); // teams are locked
    }

    /**
     * @test
     */
    public function cannotManageTeamSetWhenLocked()
    {
        $team = $this->teamSet->teams->last();

        // attach a player to this team to ensure that doesn't break deletion
        $team->players()->attach($this->group->players()->first()->id);

        // update the team name
        $newTeamName = uniqid();
        $this
            ->patch('/teams/'.$team->id, [
                'name' => $newTeamName,
            ])
            ->assertResponseStatus(403); // teams are locked

        $this->assertNotEquals($newTeamName, Team::find($team->id)->name);

        // delete the team
        $this
            ->delete('/teams/'.$team->id)
            ->assertResponseStatus(403); // teams are locked
    }

    /**
     * @test
     */
    public function cannotManagePlayersWhenTeamsLocked()
    {
        $team = $this->teamSet->teams()->first();

        // add a player
        $this
            ->post('/teams/'.$team->id.'/addPlayer', [
                'playerId' => time(),
            ])
            ->assertResponseStatus(403); // teams are locked

        // remove a player
        $this
            ->post('/teams/'.$team->id.'/removePlayer', [
                'playerId' => time(),
            ])
            ->assertResponseStatus(403); // teams are locked
    }

    /**
     * @test
     */
    public function canReorderPlayers()
    {
        $team = $this->teamSet->teams->get(2);
        $startingPlayerOrder = [
            $team->players->first()->id,
            $team->players->get(1)->id,
        ];

        $this
            ->post('/teams/'.$team->id.'/updateOrder', [
                'sortOrder' => [
                    $startingPlayerOrder[1],
                    $startingPlayerOrder[0],
                ],
            ])
            ->assertResponseStatus(403); // teams are locked
    }
}
