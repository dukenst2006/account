<?php

use BibleBowl\Group;

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
            ->visit('/team/1/download')
            ->assertResponseOk();
    }

}