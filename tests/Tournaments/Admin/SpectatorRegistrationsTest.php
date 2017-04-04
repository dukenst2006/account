<?php

use App\Tournament;

class SpectatorRegistrationsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function canSearchSpectators()
    {
        $tournament = Tournament::active()->firstOrFail();
        $searchableSpectator = $tournament->spectators->get(0);
        $shouldntSeeSpectator = $tournament->spectators->get(1);

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/spectators')
            ->see($shouldntSeeSpectator->last_name)
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/spectators?q='.$searchableSpectator->last_name)
            ->dontSee($shouldntSeeSpectator->last_name);
    }

    /** @test */
    public function canViewSpectator()
    {
        $tournament = Tournament::active()->firstOrFail();
        $spectator = $tournament->spectators->get(0);

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/spectators')
            ->click($spectator->full_name)
            ->see($spectator->full_name)
            ->see($spectator->email);
    }
}
