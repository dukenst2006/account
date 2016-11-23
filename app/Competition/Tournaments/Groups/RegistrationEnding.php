<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Tournament;
use BibleBowl\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationEnding extends Notification implements ShouldQueue
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
        $message->subject('Registration Ending Soon');
        $message->greeting($this->tournament->name.' Registration');
        $message->line('Registration for '.$this->tournament->name.' ends on '.$this->tournament->registration_end->format('l F j').'.  Register any additional players, quizmasters or spectators before this date.');
        $message->action('Manage Registration', url('tournaments/'.$this->tournament->slug.'/group'));

        return $message;
    }
}
