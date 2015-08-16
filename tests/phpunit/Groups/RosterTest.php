<?php

class RosterTest extends TestCase
{

    use \Lib\ActingAsHeadCoach;

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
            ->see($player->full_name);

        # Test Inactive Players
        $this->see('1 inactive players');
    }

}