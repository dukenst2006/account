<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Group;
use BibleBowl\Tournament;
use BibleBowl\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PLayerInvitationRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Tournament */
    protected $tournament;

    /** @var string */
    protected $playerName;

    /** @var Group */
    protected $group;

    /** @var string */
    protected $team;

    /** @var User */
    protected $requester;

    public function __construct(Tournament $tournament, string $playerName, Group $group, string $team, User $requester)
    {
        $this->tournament = $tournament;
        $this->playerName = $playerName;
        $this->group = $group;
        $this->team = $team;
        $this->requester = $requester;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        $message = new MailMessage();
        $message->subject('Player Invitation Request');
        $message->greeting($this->tournament->name.' Player Invitation Request');
        $message->line('<strong>'.$this->requester->full_name.'</strong> has requested that <strong>'.$this->playerName.'</strong> play on <strong>'.$this->team.'</strong> on behalf of <strong>'.$this->group->name.'</strong>.');
        $message->line('As the tournament coordinator it is up to you to evaluate eligibility of this request.  If you approve this request, please forward it to <strong>bkuhl@biblebowl.org</strong>.  Whether you approve, reject or have other questions please contact <strong>'.$this->requester->full_name.' ('.$this->requester->email.')</strong> directly.');
        $message->line('If approved, '.$this->requester->full_name.' will need to submit any relevant payment for this player before their registration is final.');

        return $message;
    }
}
