<?php

use BibleBowl\TeamSet;
use BibleBowl\Team;
use BibleBowl\Group;
use BibleBowl\Users\Auth\SessionManager;

class TeamsTest extends TestCase
{

    use \Helpers\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /**
     * @test
     */
    public function canViewPdf()
    {
        $this
            ->visit('/teamsets/1/pdf')
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function canUpdateTeamSetName()
    {
        // avoiding CSRF token issue
        $this->withoutMiddleware();

        $this->withSession([
            SessionManager::GROUP   => Group::findOrFail(2)->toArray()
        ]);

        $teamSet = TeamSet::findOrFail(1);

        $this
            ->patch('/teamsets/'.$teamSet->id, [
                'name' => $name = time()
            ])
            ->assertEquals($name, TeamSet::findOrFail($teamSet->id)->name);

        $teamSet->save();
    }

    /**
     * @test
     */
    public function canManageTeams()
    {
        // avoiding CSRF token issue
        $this->withoutMiddleware();

        $group = Group::findOrFail(2);
        $this->withSession([
            SessionManager::GROUP   => $group->toArray()
        ]);

        $teamSet = TeamSet::findOrFail(1);

        // create a team
        $this
            ->post('/teamsets/'.$teamSet->id.'/createTeam', [
                'name' => $name = time()
            ])
            ->assertResponseOk();

        $team = $teamSet->teams->last();
        $this->assertEquals($name, $team->name);

        // attach a player to this team to ensure that doesn't break deletion
        $team->players()->attach($group->players()->first()->id);

        // update the team name
        $newTeamName = uniqid();
        $this
            ->patch('/teams/'.$team->id, [
                'name' => $newTeamName
            ])
            ->assertResponseOk();

        $this->assertEquals($newTeamName, Team::find($team->id)->name);

        // delete the team
        $this
            ->delete('/teams/'.$team->id)
            ->assertResponseOk();

        $this->assertNull(Team::find($team->id));
    }

    /**
     * @test
     */
    public function canManagePlayers()
    {
        // avoiding CSRF token issue
        $this->withoutMiddleware();

        $group = Group::findOrFail(2);
        $this->withSession([
            SessionManager::GROUP   => $group->toArray()
        ]);

        $teamSet = TeamSet::findOrFail(1);
        $team = $teamSet->teams()->first();
        $playerId = $group->players()->first()->id;
        $startCount = $team->players()->count();

        // add a player
        $this
            ->post('/teams/'.$team->id.'/addPlayer', [
                'playerId' => $playerId
            ])
            ->assertResponseOk();

        $this->assertEquals($startCount+1, $team->players()->count());

        // verify the correct player was added and that we have record of
        // the DateTime of that event
        $player = $team->players()->first();
        $this->assertEquals($playerId, $player->id);
        $this->assertNotEquals('0000-00-00 00:00:00', $player->created_at);
        $this->assertNotEquals('0000-00-00 00:00:00', $player->updated_at);

        // remove a player
        $this
            ->post('/teams/'.$team->id.'/removePlayer', [
                'playerId' => $playerId
            ])
            ->assertResponseOk();

        // verify we're at the same count we were when we started
        $this->assertEquals($startCount, $team->players()->count());
    }

    /**
     * @test
     */
    public function canReorderPlayers()
    {
        // avoiding CSRF token issue
        $this->withoutMiddleware();

        $group = Group::findOrFail(2);
        $this->withSession([
            SessionManager::GROUP   => $group->toArray()
        ]);

        $teamSet = TeamSet::findOrFail(1);
        $team = $teamSet->teams->get(2);
        $startingPlayerOrder = [
            $team->players->first()->id,
            $team->players->get(1)->id
        ];

        $this
            ->post('/teams/'.$team->id.'/updateOrder', [
                'sortOrder' => [
                    $startingPlayerOrder[1],
                    $startingPlayerOrder[0]
                ]
            ])
            ->assertResponseOk();

        $team = Team::findOrFail($team->id);

        $this->assertEquals($startingPlayerOrder[1], $team->players->first()->id);
        $this->assertEquals($startingPlayerOrder[0], $team->players->get(1)->id);
    }

    /**
     * @test
     */
    public function canCopyAndDeleteTeamSets()
    {
        // avoiding CSRF token issue
        $this->withoutMiddleware();

        $teamSetName = 'Team Copy '.time();
        $this
            ->visit('/teamsets/create')
            ->type($teamSetName, 'name')
            ->select(1, 'teamSet')
            ->press('Save')
            ->see($teamSetName);

        $teamSet = TeamSet::where('name', $teamSetName)->get()->first();
        $this->assertGreaterThan(0, $teamSet->teams()->count());

        $this->call('DELETE', '/teamset/'.$teamSet->id);
        $this->dontSee($teamSetName);
    }
}
