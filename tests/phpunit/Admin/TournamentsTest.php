<?php

use Carbon\Carbon;
use BibleBowl\Tournament;

class TournamentsTest extends TestCase
{

    use \Lib\Roles\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /**
     * @test
     */
    public function canCreateTournament()
    {
        $name = 'My Tournament '.time();
        $soon = Carbon::now()->addMonths(1)->format('m/d/Y');

        $this
            ->visit('/admin/tournaments/create')
            ->type($name, 'name')
            ->press('Save')
            ->see('A registration start date is required')

            ->type($soon, 'start')
            ->type($soon, 'end')
            ->type($soon, 'registration_start')
            ->type($soon, 'registration_end')
            ->press('Save')
            ->landOn('/admin/tournaments')
            ->see($name);

        # Cleaning up
        Tournament::where('name', $name)->delete();
    }

    /**
     * @test
     */
    public function viewUser()
    {
        $tournament = Tournament::first();

        $this
            ->visit('/admin/tournaments/'.$tournament->id)
            ->see($tournament->name);
    }

}