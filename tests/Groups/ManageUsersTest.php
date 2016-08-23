<?php

use BibleBowl\Invitation;
use BibleBowl\Group;
use BibleBowl\User;
use Helpers\ActingAsDirector;
use Helpers\ActingAsHeadCoach;

class ManageUsersTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use ActingAsHeadCoach, ActingAsDirector {
        ActingAsDirector::season insteadOf ActingAsHeadCoach;
    }

    /**
     * @test
     */
    public function canInviteUsersToGroup()
    {
        $this->setupAsHeadCoach();

        $email = 'test-user@nothing.com';
        $this
            ->visit('/group/'.$this->group()->id.'/settings/users')
            ->see($this->headCoach()->full_name)
            ->see('Owner')
            ->click('Invite User')
            ->type($email, 'email')
            ->press('Send Invitation')
            ->see('Invitation has been sent')
            ->see('Pending Invitations')
            ->see($email);

        $invitation = Invitation::where('email', $email)->first();
        $this->assertEquals(Invitation::SENT, $invitation->status);
    }

    /**
     * @test
     */
    public function expiresAfterAlreadyAccepted()
    {
        $group = Group::active()->firstOrFail();
        $invitation = Invitation::create([
            'status'        => Invitation::ACCEPTED,
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'inviter_id'    => $group->owner_id,
            'group_id'      => $group->id
        ]);

        $this
            ->visit('/invitation/'.$invitation->guid.'/accept')
            ->see('This invitation has expired or has already been claimed');
    }

    /**
     * @test
     */
    public function expiresAfterAlreadyDeclined()
    {
        $group = Group::active()->firstOrFail();
        $invitation = Invitation::create([
            'status'        => Invitation::DECLINED,
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'inviter_id'    => $group->owner_id,
            'group_id'      => $group->id
        ]);

        $this
            ->visit('/invitation/'.$invitation->guid.'/decline')
            ->see('This invitation has expired or has already been claimed');
    }

    /**
     * @test
     */
    public function canDeclineGroupInvitations()
    {
        $group = Group::active()->firstOrFail();
        $invitation = Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'inviter_id'    => $group->owner_id,
            'group_id'      => $group->id
        ]);

        $this
            ->visit('/invitation/'.$invitation->guid.'/decline')
            ->see('Invitation has been declined');

        $invitation = Invitation::findOrFail($invitation->id);
        $this->assertEquals(Invitation::DECLINED, $invitation->status);
    }

    /**
     * @test
     */
    public function canAcceptGroupInvitations()
    {
        $group = Group::active()->firstOrFail();
        $invitation = Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_GROUP,
            'inviter_id'    => $group->owner_id,
            'group_id'      => $group->id
        ]);

        $this->setupAsDirector();
        $this
            ->visit('/invitation/'.$invitation->guid.'/accept')
            ->followRedirects()
            ->see('Invitation has been accepted')
            ->assertSessionHas(\BibleBowl\Users\Auth\SessionManager::GROUP, $invitation->group->toArray());

        $invitation = Invitation::findOrFail($invitation->id);
        $this->assertEquals(Invitation::ACCEPTED, $invitation->status);
    }

    /**
     * @test
     */
    public function canRetractGroupInvitations()
    {
        $this->setupAsHeadCoach();
        $this
            ->visit('/group/'.$this->group()->id.'/settings/users')
            ->click('Retract')
            ->see('Invitation has been retracted');

        $this->assertEquals(0, $this->group()->invitations()->count());
    }

    /**
     * @test
     */
    public function canRemoveUsers()
    {
        $guardian = User::where('email', DatabaseSeeder::GUARDIAN_EMAIL)->first();

        $this->setupAsHeadCoach();
        $this->group()->addHeadCoach($guardian);

        $this
            ->visit('/group/'.$this->group()->id.'/settings/users')
            ->see($guardian->full_name)
            ->click('Remove')
            ->see('User has been removed');

        $this->assertEquals(1, $this->group()->users()->count());
    }
}
