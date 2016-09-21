<?php

namespace BibleBowl\Groups;

use BibleBowl\Group;
use BibleBowl\Seasons\GroupRegistration;
use BibleBowl\User;
use Illuminate\Mail\Message;
use Mail;

class RegistrationConfirmation
{
    public function send(
        User $recipient,
        Group $group,
        GroupRegistration $registration,
        $queued = true,
        $contentBody = null
    ) {
        // Since this email is queued, we need to get pivot data now and include it with $players
        // because once it actually gets processed $players won't be an object making it more
        // difficult to fetch this data
        $players = [];
        $grades = [];
        $shirtSizes = [];
        foreach ($registration->players($group->program) as $player) {
            $player->full_name = $player->full_name;
            $grades[$player->id] = $registration->grade($player->id);
            $shirtSizes[$player->id] = $registration->shirtSize($player->id);
            $players[] = $player;
        }

        // setting this value so that it's available in the toArray() so queued mail can use it
        $recipient->full_name = $recipient->full_name;

        $view = 'emails.group-welcome-email';
        $viewData = [
            'groupId'       => $group->id,
            'guardian'      => $recipient,
            'players'       => $players,
            'grades'        => $grades,
            'shirtSizes'    => $shirtSizes,
            'hasEmailBody'  => is_null($contentBody) ? $group->settings->hasRegistrationEmailContents() : true,
            'emailBody'     => is_null($contentBody) ? $group->settings->registrationEmailContents() : $contentBody,
        ];
        $callBack = function (Message $message) use ($group, $recipient) {
            $message->to($recipient->email, $recipient->full_name)
                ->subject($group->name.' Registration');
        };

        if ($queued) {
            Mail::queue($view, $viewData, $callBack);
        } else {
            Mail::send($view, $viewData, $callBack);
        }
    }

    /**
     * Send a sample email.
     *
     * @param User $user
     */
    public function sendTest(User $user, Group $group, $contentBody)
    {
        /** @var GroupRegistrationTest $registration */
        $registration = app(GroupRegistrationTest::class);
        $registration->addGroup($group);

        $this->send($user, $group, $registration, $isQueued = false, $contentBody);
    }
}
