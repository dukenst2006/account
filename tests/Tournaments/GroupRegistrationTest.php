<?php

use BibleBowl\TournamentQuizmaster;
use BibleBowl\Tournament;
use Carbon\Carbon;

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

        // verify the quizzing preferences email is sent
        Mail::shouldReceive('queue')->once();

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

    /**
     * @test
     */
    public function quizmasterCanProvideQuizzingPreferences()
    {
        $tournament = Tournament::firstOrFail();
        $tournamentQuizmaster = TournamentQuizmaster::firstOrFail();
        $shirtSize = 'XL';
        $gamesQuizzed = 'Fewer than 15';
        $this
            ->visit('/tournaments/'.$tournament->slug.'/registration/quizmaster-preferences/'.$tournamentQuizmaster->guid)
            ->select($gamesQuizzed, 'games_quizzed_this_season')
            ->select($shirtSize, 'shirt_size')
            ->press('Save')
            ->see('Your quizzing preferences have been updated');

        $tournamentQuizmaster = TournamentQuizmaster::firstOrFail();
        $this->assertEquals($shirtSize, $tournamentQuizmaster->shirt_size);
        $this->assertEquals($gamesQuizzed, $tournamentQuizmaster->quizzing_preferences->gamesQuizzedThisSeason());
    }

    /**
     * @test
     */
    public function cantAddQuizmastersWhenRegistrationClosed()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->update([
            'registration_start'    => Carbon::now()->subDays(10)->format('m/d/Y'),
            'registration_end'      => Carbon::now()->subDays(1)->format('m/d/Y')
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug.'/group')
            ->dontSee('Add Quizmaster');
    }
}
