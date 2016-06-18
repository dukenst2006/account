<?php

use BibleBowl\Tournament;
use Helpers\ActingAsGuardian;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Helpers\SimulatesTransactions;
use BibleBowl\TournamentQuizmaster;
use BibleBowl\Group;
use BibleBowl\ParticipantType;

class GroupRegistrationTest extends TestCase
{

    use DatabaseTransactions;
    use ActingAsGuardian;
    use SimulatesTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
    }

    /**
     * @test
     */
    public function canRegisterWithoutGroupAndWithFees()
    {
        $this->simulateTransaction();

        $gamesQuizzedThisSeason = 'Fewer than 30';
        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/tournaments/'.$tournament->slug.'/registration/quizmaster')
            ->select($gamesQuizzedThisSeason, 'games_quizzed_this_season')
            ->press('Continue')
            ->seePageIs('/cart')
            ->see('Quizmaster Tournament Registration')
            ->press('Submit')
            ->see('Your quizmaster registration is complete');

        $quizmaster = TournamentQuizmaster::orderBy('id', 'desc')->first();

        // defaults to no group selected
        $this->assertNull($quizmaster->group_id);

        // quizzing preferences saved
        $this->assertEquals($gamesQuizzedThisSeason, $quizmaster->quizzing_preferences->gamesQuizzedThisSeason());

        // we use the receipt_id to determine if payment has been made
        $this->assertGreaterThan(0, $quizmaster->receipt_id);
    }

    /**
     * @test
     */
    public function canRegisterWithGroupAndWithoutFees()
    {
        $this->simulateTransaction();

        $gamesQuizzedThisSeason = 'Fewer than 30';
        $tournament = Tournament::firstOrFail();

        // Remove fees for quizmasters
        $tournament->participantFees()
            ->where('participant_type_id', ParticipantType::QUIZMASTER)
            ->update([
                'earlybird_fee' => 0,
                'fee' => 0
        ]);

        $group = Group::byProgram($tournament->program_id)->first();
        $this
            ->visit('/tournaments/'.$tournament->slug.'/registration/quizmaster')
            ->select($group->id, 'group_id')
            ->select($gamesQuizzedThisSeason, 'games_quizzed_this_season')
            ->press('Submit')
            ->see('Your quizmaster registration is complete');

        $quizmaster = TournamentQuizmaster::orderBy('id', 'desc')->first();

        $this->assertEquals($group->id, $quizmaster->group_id);

        // quizzing preferences saved
        $this->assertEquals($gamesQuizzedThisSeason, $quizmaster->quizzing_preferences->gamesQuizzedThisSeason());

        // no payment was made, so we shouldn't have a receipt
        $this->assertNull($quizmaster->receipt_id);
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
            ->press('Quizmaster'); // asserts it's a button
    }
}
