<?php

namespace App\Groups;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteHeadCoach extends Mailable implements ShouldQueue
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
        return $this->subject('Bible Bowl Group Management Invitation')
            ->markdown('emails.group-user-invitation')
            ->with([
                'header'         => 'Group Management Invitation',
                'invitationText' => '**'.$this->invitation->inviter->full_name.'** has invited you to help manage the '.$this->invitation->group->program->abbreviation.' **'.$this->invitation->group->name.'** group.',
                'invitation'     => $this->invitation,
            ]);
    }
}
