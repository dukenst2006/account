<?php

namespace BibleBowl\Competition\Tournaments\Spectators;

use BibleBowl\Spectator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(Spectator $spectator) : MailMessage
    {
        $tournament = $spectator->tournament;

        $message = new MailMessage();
        $message->subject($spectator->type().' Registration Confirmation');
        $message->greeting($tournament->name.' Registration');

        if ($spectator->wasRegisteredByHeadCoach()) {
            $groupDescription = '';
            if ($spectator->hasGroup()) {
                $groupDescription .= ' with '.$spectator->group->name;
            }

            $message->line("You've been registered ".$groupDescription.' for '.$tournament->name.' which takes place '.$tournament->dateSpan().'.');
        } else {
            $message->line('Your '.strtolower($spectator->type()).' '.$tournament->name.' registration which takes place '.$tournament->dateSpan().' has been received.');
        }

        if ($spectator->isFamily()) {
            $familyMembers = ['you'];
            if ($spectator->hasSpouse()) {
                $familyMembers[] = $spectator->spouse_first_name;
            }
            foreach ($spectator->minors as $minor) {
                $familyMembers[] = $minor->name;
            }

            $message->line('This registration includes '.implode(', ', $familyMembers).' and any children under 2.');
        }

        return $message;
    }
}
