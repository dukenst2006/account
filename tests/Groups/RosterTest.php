<?php

class RosterTest extends TestCase
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
    public function viewRoster()
    {
        $player = $this->group->players()->active($this->season)->first();

        $this
            ->visit('/roster')
            ->see('Player Roster')
            ->see('Download CSV')

            // last names are hidden on mobile, so we can't assert them as a single string
            ->see($player->first_name)
            ->see($player->last_name)

            // Test toggling active/inactive players
            ->click('#deactivate-'.$player->id)
            ->seePageIs('/roster')
            ->dontSee($player->last_name.', '.$player->first_name)
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

    /**
     * @test
     */
    public function viewGuardian()
    {
        $guardian = $this->group->guardians($this->season())->first();

        $this
            ->visit('/guardian/'.$guardian->id);
    }
}
