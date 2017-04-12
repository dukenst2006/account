<?php

use App\Player;
use App\Team;
use App\Tournament;

class TeamTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function providesTeamsThatMeetMinimum()
    {
        $tournament = Tournament::firstOrFail();
        $team = Team::firstOrFail();

        $this->assertEquals(1, Team::withEnoughPlayers($tournament)->count());

        $team->players()->sync(Player::limit(3)->get());

        $this->assertEquals(2, Team::withEnoughPlayers($tournament)->count());
    }

    /** @test */
    public function providesTeamsThatMeetMaximum()
    {
        $tournament = Tournament::firstOrFail();
        $team = Team::firstOrFail();

        $team->players()->sync(Player::limit(7)->get());

        $this->assertEquals(1, Team::withEnoughPlayers($tournament)->count());

        $team->players()->sync(Player::limit(6)->get());

        $this->assertEquals(2, Team::withEnoughPlayers($tournament)->count());
    }
}
