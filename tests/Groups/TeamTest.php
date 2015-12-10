<?php

use BibleBowl\TeamSet;
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
            ->visit('/team/1/pdf')
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
            ->patch('/team/'.$teamSet->id, [
                'name' => $name = time()
            ])
            ->assertEquals($name, TeamSet::findOrFail($teamSet->id)->name);

        $teamSet->save();
    }

}