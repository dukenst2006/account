<?php

use BibleBowl\User;
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

    /**
     * @test
     */
    public function searchUserList()
    {
        $this
            ->visit('/admin/players')
            ->see('David Webb')
            ->visit('/admin/groups?q=joe')
            ->dontSee('David Webb');
    }

    /**
     * @test
     */
    public function viewPlayer()
    {
        $player = Player::first();

        $this
            ->visit('/admin/players/'.$player->id)
            ->see($player->full_name);
    }

    /**
     * @test
     */
    public function canDeletePlayer()
    {
        $player = Player::first();

        $this
            ->visit('/admin/players/'.$player->id)
            ->press('Delete Player');

        $this->assertFalse($player->guardian->is(Role::ADMIN));
    }
}
