<?php

use App\Invitation;
use App\Tournament;
use App\User;
use Helpers\ActingAsDirector;
use Helpers\ActingAsHeadCoach;
use Helpers\ActingAsQuizmaster;

class ManageCoordinatorsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use ActingAsHeadCoach, ActingAsQuizmaster, ActingAsDirector {
        ActingAsDirector::season insteadof ActingAsHeadCoach;
    }

    /** @test */
    public function canInviteCoordinatorsToTournament()
    {
        $this->setupAsDirector();

        $email = 'test-user@nothing.com';
        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/coordinators')
            ->see($this->director()->full_name)
            ->see('Owner')
            ->click('Invite Coordinator')
            ->type($email, 'email')
            ->press('Send Invitation')
            ->see('Invitation has been sent')
            ->see('Pending Invitations')
            ->see($email);

        $invitation = Invitation::where('email', $email)->first();
        $this->assertEquals(Invitation::SENT, $invitation->status);
    }

    /** @test */
    public function expiresAfterAlreadyAccepted()
    {
        $tournament = Tournament::firstOrFail();
        $invitation = Invitation::create([
            'status'        => Invitation::ACCEPTED,
            'type'          => Invitation::TYPE_MANAGE_TOURNAMENTS,
            'inviter_id'    => $tournament->creator_id,
            'tournament_id' => $tournament->id,
        ]);

        $this
            ->visit('/invitation/'.$invitation->guid.'/accept')
            ->see('This invitation has expired or has already been claimed');
    }

    /** @test */
    public function expiresAfterAlreadyDeclined()
    {
        $tournament = Tournament::firstOrFail();
        $invitation = Invitation::create([
            'status'        => Invitation::DECLINED,
            'type'          => Invitation::TYPE_MANAGE_TOURNAMENTS,
            'inviter_id'    => $tournament->creator_id,
            'tournament_id' => $tournament->id,
        ]);

        $this
            ->visit('/invitation/'.$invitation->guid.'/decline')
            ->see('This invitation has expired or has already been claimed');
    }

    /** @test */
    public function canDeclineTournamentInvitations()
    {
        $tournament = Tournament::firstOrFail();
        $invitation = Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_TOURNAMENTS,
            'inviter_id'    => $tournament->creator_id,
            'tournament_id' => $tournament->id,
        ]);

        $this
            ->visit('/invitation/'.$invitation->guid.'/decline')
            ->see('Invitation has been declined');

        $invitation = Invitation::findOrFail($invitation->id);
        $this->assertEquals(Invitation::DECLINED, $invitation->status);
    }

    /** @test */
    public function canAcceptTournamentInvitations()
    {
        $tournament = Tournament::firstOrFail();
        $invitation = Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_TOURNAMENTS,
            'inviter_id'    => $tournament->creator_id,
            'tournament_id' => $tournament->id,
        ]);

        $this->setupAsHeadCoach();
        $tournament->removeCoordinator($this->headCoach());
        $this->assertEquals(0, $this->headCoach()->tournaments()->count());
        $this
            ->visit('/invitation/'.$invitation->guid.'/accept')
            ->followRedirects()
            ->see('Invitation has been accepted');

        $invitation = Invitation::findOrFail($invitation->id);
        $this->assertEquals(Invitation::ACCEPTED, $invitation->status);
        $this->assertEquals(1, $this->headCoach()->tournaments()->count());
    }

    /** @test */
    public function canRetractTournamentInvitations()
    {
        $this->setupAsDirector();
        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/coordinators')
            ->click('Retract')
            ->see('Invitation has been retracted');

        $this->assertEquals(0, $tournament->invitations()->count());
    }

    /** @test */
    public function canRemoveCoordinators()
    {
        $this->setupAsDirector();
        $tournament = Tournament::firstOrFail();

        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/coordinators')
            ->see($this->director()->full_name)
            ->click('Remove')
            ->see('Coordinator has been removed');

        $this->assertEquals(1, $tournament->coordinators()->count());
    }
}
