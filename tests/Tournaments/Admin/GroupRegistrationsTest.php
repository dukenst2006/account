<?php

use App\Group;
use App\Tournament;

class GroupRegistrationsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function canSearchGroups()
    {
        $tournament = Tournament::active()->firstOrFail();
        $searchableGroup = Group::findOrFail(1);
        $shouldntSeeGroup = Group::findOrFail(2);

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/groups')
            ->see($searchableGroup->name)
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/groups?q='.$searchableGroup->name)
            ->dontSee($shouldntSeeGroup->name);
    }

    /** @test */
    public function canViewGroup()
    {
        $tournament = Tournament::active()->firstOrFail();
        $group = Group::firstOrFail();

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/registrations/groups')
            ->click($group->name)
            ->see($group->name);
    }
}
