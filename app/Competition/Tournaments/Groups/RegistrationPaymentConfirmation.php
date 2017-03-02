<?php

namespace App\Competition\Tournaments\Groups;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationPaymentConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Registration */
    protected $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $headCoach) : MailMessage
    {
        $tournament = $this->registration->tournament();

        $message = new MailMessage();
        $message->subject($tournament->name.' Registration Confirmation');
        $message->greeting($tournament->name.' Registration');
        $message->line("Your group's latest registration payment has been received.");
        $message->action('Manage Registration', url('tournaments/'.$tournament->slug.'/group'));
        $message->line("Don't forget that any participant's registration listed as requiring payment are incomplete until payment has been submitted.");

        return $message;
    }
}
