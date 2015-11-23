<?php

use BibleBowl\Group;

class ManageTest extends TestCase
{

    use \Lib\Roles\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /**
     * @test
     */
    public function searchGroupsBeforeCreating()
    {
        $this
            ->visit('/group/create/search')
            ->dontSee("I don't see my group")
            ->type('South', 'q')
            ->visit('/group/create/search?q=South')
            ->see('Southeast Christian Church')
            ->click("I don't see my group")
            ->landOn('/group/create');
    }

    /**
     * @test
     */
    public function createGroup()
    {
        $groupName = 'Group '.time();
        $this
            ->visit('/group/create')
            ->see('New Group')
            ->press('Save')
            ->see('The name field is required')

            # verify creating user agrees that they're the Head Coach
            ->see('Only the Head Coach may create this group')

            ->type($groupName, 'name')
            ->check('amHeadCoach')
            ->press('Save')
            ->landOn('/dashboard')
            ->see($groupName.' has been created');

        $this->deleteLastGroup();
    }

    /**
     * @test
     */
    public function editGroup()
    {
        $newGroupName = 'Group '.uniqid();
        $this
            ->visit('/group/'.$this->group()->id.'/edit')
            ->see($this->group()->name)
            ->press('Save')
            ->see('Your changes were saved')
            ->type($newGroupName, 'name')
            ->press('Save')
            ->see('Your changes were saved')

            # reset it back
            ->visit('/group/'.$this->group()->id.'/edit')
            ->type(DatabaseSeeder::GROUP_NAME, 'name')
            ->press('Save');
    }

    /**
     * @test
     */
    public function canToggleInactiveState()
    {
        $group = Group::findOrFail($this->group()->id);
        $this->assertNull($group->inactive);

        # make inactive
        $this
            ->visit('/group/'.$group->id.'/edit')
            ->check('inactive')
            ->press('Save');
        $group = Group::findOrFail($this->group()->id);
        $this->assertNotNull($group->inactive);

        # make inactive
        $this
            ->visit('/group/'.$group->id.'/edit')
            ->uncheck('inactive')
            ->press('Save');
        $group = Group::findOrFail($this->group()->id);
        $this->assertNull($group->inactive);

    }

    private function deleteLastGroup()
    {
        $group = Group::all()->last();
        $group->users()->sync([]);
        $group->delete();
    }

}