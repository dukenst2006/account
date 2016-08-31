<?php

use BibleBowl\Group;

class ManageTest extends TestCase
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
    public function searchGroupsBeforeCreating()
    {
        $this
            ->visit('/group/create/search')
            ->see("Benefits of adding your group")
            ->dontSee("I see my group")
            ->dontSee("I don't see my group")
            ->type('South', 'q')
            ->visit('/group/create/search?q=South')
            ->see('Southeast Christian Church')
            ->see("I see my group")
            ->click("I don't see my group")
            ->seePageIs('/group/create');
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
            ->press('Save');
        $group = Group::orderBy('id', 'DESC')->first();
        $this
            ->seePageIs('/group/'.$group->id.'/settings/email')
            ->see($groupName.' has been created');
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

    /**
     * @test
     */
    public function editGroupEmailSettings()
    {
        $html = "<b>something</b>";
        $this
            ->visit('/group/'.$this->group()->id.'/settings/email')
            ->type($html, 'welcome-email')
            ->press('Save')
            ->see('Your email settings have been saved');

        $group = Group::findOrFail($this->group()->id);
        $this->assertEquals($html, $group->settings->registrationEmailContents());
    }

    /**
     * @test
     */
    public function sendTestWelcomeEmail()
    {
        $this
            ->post('/group/'.$this->group()->id.'/settings/test-email')
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function editIntegrationSettings()
    {
        $apiKey = md5(time()).'-us1';
        $listId = '34adf2345wd';
        $this
            ->visit('/group/'.$this->group()->id.'/settings/integrations')
            ->check('mailchimp-enabled')
            ->type($apiKey, 'mailchimp-key')
            ->type($listId, 'mailchimp-list-id')
            ->press('Save')
            ->see('Your integration settings have been saved');

        $group = Group::findOrFail($this->group()->id);
        $this->assertTrue($group->settings->mailchimpEnabled());
        $this->assertEquals($apiKey, $group->settings->mailchimpKey());
        $this->assertEquals($listId, $group->settings->mailchimpListId());
    }
}
