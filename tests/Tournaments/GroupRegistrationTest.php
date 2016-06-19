<?php

use BibleBowl\Tournament;
use Carbon\Carbon;
use BibleBowl\ParticipantType;

class GroupRegistrationTest extends TestCase
{

    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /**
     * @test
     */
    public function canViewGroupRegistrationStatus()
    {
        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->click('#register-group')
            ->see('Quizmasters');
    }

    /**
     * @test
     */
    public function canInviteQuizmasters()
    {
        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/tournaments/'.$tournament->slug.'/group')
            ->click('Add Quizmaster')
            ->type('John', 'first_name')
            ->type('Gutson', 'last_name')
            ->type('tester123'.time().'@no-domain.com', 'email')
            ->press('Save & Continue')
            ->see('Quizmaster has been added')
            ->see('John Gutson');
    }

    /**
     * @test
     */
    public function cantRegisterAsGuest()
    {
        // since only the guardian login is seeded, we can tear down
        // to "log out"
        $this->tearDown();
        parent::setUp();
        
        // assert there's a button on the page and we can't click it
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The selected node does not have a form ancestor');

        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->press('Group'); // asserts it's a button
    }

//    /**
//     * @test
//     */
//    public function canRegister()
//    {
//        $tournament = Tournament::firstOrFail();
//        $this
//            ->visit('/tournaments/'.$tournament->slug)
//            ->click('#register-group') // using "Group" adds a new group
//            ->see('League Teams')
//            ->see('2 players on 8 teams');
//    }
}
