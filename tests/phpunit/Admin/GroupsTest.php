<?php

use BibleBowl\Group;

class GroupsTest extends TestCase
{

    protected $group;

    use \Lib\Roles\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();

        $this->group = Group::where('name', DatabaseSeeder::GROUP_NAME)->first();
    }

    /**
     * @test
     */
    public function searchGroupList()
    {
        $this
            ->visit('/admin/groups')
            ->see('Southeast')
            ->visit('/admin/groups?q=Mount')
            ->click($this->group->name)
            ->landOn('/admin/groups/'.$this->group->id);
    }

    /**
     * @test
     */
    public function viewGroup()
    {

        $this
            ->visit('/admin/groups/'.$this->group->id)
            ->see($this->group->owner->full_name);
    }

}