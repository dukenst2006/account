<?php

use App\Tournament;

class QuizmasterRegistrationsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function canSearchQuizmasters()
    {
        $tournament = Tournament::active()->firstOrFail();
        $searchableQuizmaster = $tournament->tournamentQuizmasters->get(0);
        $shouldntSeeQuizmaster = $tournament->tournamentQuizmasters->get(1);

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/quizmasters')
            ->see($shouldntSeeQuizmaster->last_name)
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/quizmasters?q='.$searchableQuizmaster->last_name)
            ->dontSee($shouldntSeeQuizmaster->last_name);
    }

    /** @test */
    public function canViewQuizmaster()
    {
        $tournament = Tournament::active()->firstOrFail();
        $quizmaster = $tournament->tournamentQuizmasters->get(0);

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/quizmasters')
            ->click($quizmaster->full_name)
            ->see($quizmaster->full_name)
            ->see($quizmaster->email);
    }
}
