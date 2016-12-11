<?php

use BibleBowl\Player;
use BibleBowl\Role;

class PlayersTest extends TestCase
{
    protected $user;

    use \Helpers\ActingAsDirector;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function searchUserList()
    {
        $this
            ->visit('/admin/players')
            ->see('Webb, David')
            ->visit('/admin/groups?q=joe')
            ->dontSee('Webb, David');
    }

    /** @test */
    public function viewPlayer()
    {
        $player = Player::first();

        $this
            ->visit('/admin/players/'.$player->id)
            ->see($player->full_name);
    }

    /** @test */
    public function canDeletePlayer()
    {
        $player = Player::first();
        $player->guardian->players()->where('players.id', '!=', $player->id)->delete();

        $this->assertTrue($player->guardian->isAn(Role::GUARDIAN));

        $this
            ->visit('/admin/players/'.$player->id)
            ->press('Delete Player')
            ->see('Player has been deleted');

        Bouncer::refresh();
        $this->assertTrue($player->guardian->isNotAn(Role::GUARDIAN));
    }
}
