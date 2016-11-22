<?php

namespace BibleBowl\Competition\Tournaments\Quizmasters;

use BibleBowl\TournamentQuizmaster;
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

    public function toMail(TournamentQuizmaster $quizmaster) : MailMessage
    {
        $tournament = $quizmaster->tournament;

        $message = new MailMessage();
        $message->subject('Quizmaster Registration Confirmation');
        $message->greeting($tournament->name.' Quizzing Confirmation');

        if ($quizmaster->wasRegisteredByHeadCoach()) {
            $groupDescription = '';
            if ($quizmaster->hasGroup()) {
                $groupDescription .= ' for '.$quizmaster->group->name;
            }

            $message->line("You've been registered to quiz".$groupDescription.' at '.$tournament->name.' which takes place '.$tournament->dateSpan().'.');

            if ($tournament->settings->shouldCollectQuizmasterPreferences() && $quizmaster->hasQuizzingPreferences() == false) {
                $message->line("We'd like to do our best to accommodate your quizzing preferences so please share your quizzing preferences with us.");
                $message->action('Share Quizzing Preferences', url('tournaments/'.$tournament->slug.'/registration/quizmaster-preferences/'.$quizmaster->guid));
            }
        } else {
            $message->line('Your registration to quiz at '.$tournament->name.' which takes place '.$tournament->dateSpan().' has been received.');
        }

        return $message;
    }
}
