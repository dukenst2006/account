<?php

use BibleBowl\Player;
use BibleBowl\Users\Auth\SessionManager;

class PlayerTest extends TestCase
{

    protected $firstName = 'Lucy';
    protected $lastName = 'Tharn';

    use \Helpers\ActingAsGuardian;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
        $this->withSession([
            SessionManager::SEASON  => $this->season->toArray()
        ]);
    }

    /**
     * @test
     */
    public function canCreatePlayers()
    {
        $this
            ->visit('/player/create')
            ->type($this->lastName, 'last_name')
            ->type('05/14/2001', 'birthday')
            ->press('Save')
            ->see('The first name field is required.')
            ->type($this->firstName, 'first_name')
            ->press('Save')
            ->see($this->firstName.' '.$this->lastName.' has been added');
    }

    /**
     * @test
     */
    public function canEditPlayers()
    {
        $player = $this->guardian->players()->first();
        $newName = time();
        $this
            ->visit('/dashboard')
            ->click('#edit-child-'.$player->id)
            ->type($newName, 'first_name')
            ->press('Save')
            ->see('Your changes were saved')
            ->see($newName);
    }
}
