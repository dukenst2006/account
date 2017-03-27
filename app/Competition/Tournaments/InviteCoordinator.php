<?php

namespace App\Competition\Tournaments;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteCoordinator extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Invitation */
    protected $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bible Bowl Tournament Coordinator Invitation')
            ->markdown('emails.tournament-user-invitation')
            ->with([
                'header'         => 'Tournament Coordinator Invitation',
                'invitationText' => '**'.$this->invitation->inviter->full_name.'** has invited you to help coordinate the '.$this->invitation->tournament->name.' - '.$this->invitation->tournament->season->name.'.',
                'invitation'     => $this->invitation,
            ]);
    }
}
