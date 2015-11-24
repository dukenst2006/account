<?php

use BibleBowl\Tournament;
use Carbon\Carbon;

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
            ->seePageIs('/admin/tournaments')
            ->see($name);

        # Cleaning up
        Tournament::where('name', $name)->delete();
    }

    /**
     * @test
     */
    public function canEditTournament()
    {
        $tournament = Tournament::findOrFail(1);
        $newName = $tournament->name.time();
        $this
            ->visit('/admin/tournaments/1/edit')
            ->type($newName, 'name')
            ->press('Save')
            ->see($tournament->name);

        # Cleaning up
        $tournament->update([
            'name' => $tournament->name
        ]);
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