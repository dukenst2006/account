<?php

namespace App\Support\Bugs;

use App\Group;
use App\Tournament;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BugFixed extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Tournament */
    protected $tournament;

    /** @var Group */
    protected $group;

    public function __construct(Tournament $tournament, Group $group)
    {
        $this->tournament = $tournament;
        $this->group = $group;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        $message = new MailMessage();
        $message->subject('Quote Bee & Individual Tournament Participants');
        $message->greeting('Quote Bee & Individual Tournament Participants');
        $message->line("We just fixed a bug in the online nationals registration where a group's Quote Bee and Individual Tournament participants could be overwritten/erased.");
        $message->line("We've fixed this issue, but unfortunately some information has been lost.  Please login and review your group's participants to for these events to make sure your players are signed up who want to participate.");
        $message->line('If you have any questions please contact us at office@biblebowl.org.');
        $message->action('Manage Participants', url('tournaments/'.$this->tournament->slug.'/registration/group/events'));

        return $message;
    }
}
