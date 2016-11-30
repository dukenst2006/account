<?php

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\User;

class GroupsTest extends TestCase
{
    protected $group;

    use \Helpers\ActingAsDirector;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

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
            ->seePageIs('/admin/groups/'.$this->group->id);
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

    /**
     * @test
     */
    public function canTransferOwnership()
    {
        $guardian = User::where('email', DatabaseSeeder::GUARDIAN_EMAIL)->firstOrFail();
        $this->assertTrue($guardian->isNotAn(Role::HEAD_COACH));
        $this->assertTrue($this->group->owner->isAn(Role::HEAD_COACH));

        $this
            ->visit('/admin/groups/'.$this->group->id)
            ->click('Transfer Ownership')
            ->see('Transfer Ownership: '.$this->group->name)
            ->select($guardian->id, 'user_id')
            ->press('Transfer')
            ->see('Ownership has been transferred');

        Bouncer::refresh();
        $this->assertTrue($guardian->isAn(Role::HEAD_COACH));
        $this->assertTrue(Group::findOrFail($this->group->id)->isOwner($guardian));
    }

    /**
     * @test
     */
    public function canViewGroupsWithOutstandingRegistrationFees()
    {
        $this
            ->visit('/admin/groups/outstanding-registration-fees')
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function receivesNotificationsForOutstandingRegistrationFees()
    {
        DB::update('UPDATE player_season SET inactive = NULL, created_at = DATE_SUB(curdate(), INTERVAL 2 WEEK)');
        Artisan::call(\BibleBowl\Seasons\NotifyOfficeOfOutstandingRegistrationPayments::COMMAND);
    }
}
