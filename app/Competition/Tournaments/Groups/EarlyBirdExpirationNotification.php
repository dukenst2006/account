<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Tournament;
use BibleBowl\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EarlyBirdExpirationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Tournament */
    protected $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        $message = new MailMessage();
        $message->subject('Early Bird Registration Ending Soon');
        $message->greeting($this->tournament->name.' Early Bird Registration');
        $message->line('Early Bird registration for '.$this->tournament->name.' ends on '.$this->tournament->earlybird_ends->format('l F j').".  There are still components of your group's registration that have not yet been paid for.  Pay for them now to take advantage of this discount registration pricing.");
        $message->action('Manage Registration', url('tournaments/'.$this->tournament->slug.'/group'));

        return $message;
    }
}
