<?php

class RosterTest extends TestCase
{

    use \Lib\Roles\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /**
     * @test
     */
    public function viewRoster()
    {
        $player = $this->group->players()->active($this->season)->first();

        $this
            ->visit('/roster')
            ->see('Player Roster')
            ->see('Download CSV')
            ->see($player->full_name)

            # Test toggling active/inactive players
            ->click('#deactivate-'.$player->id)
            ->landOn('/roster')
            ->see($player->full_name)
            ->click('#inactive-roster')
            ->click('#activate-'.$player->id)
            ->see($player->full_name.' is now active');
    }

    /**
     * @test
     */
    public function viewMap()
    {
        $guardian = $this->group->guardians($this->season())->first();

        $this
            ->visit('/roster/map')
            ->see('Player Map')
            ->see($guardian->last_name.' Family');
    }

}