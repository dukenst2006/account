<?php

namespace BibleBowl\Users\Notifications;

use BibleBowl\Group;
use BibleBowl\Season;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindPendingSeasonalRegistrationFees extends Notification
{
    use Queueable;

    /** @var Group */
    protected $group;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $season = Season::current()->first();
        $players = $this->group->players()->pendingRegistrationPayment($season)->get();

        $bulletedList = '';
        foreach ($players as $player) {
            $bulletedList .= '<li>'.$player->full_name.'</li>';
        }

        $mailMessage = new MailMessage();
        $mailMessage->greeting('Registration Fee Reminder');
        $mailMessage->line('<strong>'.$this->group->name.'</strong> has <strong>'.count($players).'</strong> player(s) with outstanding '.$this->group->program->name.' seasonal registration fees.');

        $mailMessage->action('Pay Fees', url('players/pay'));

        $mailMessage->line("Here's the players with outstanding fees:");
        $mailMessage->line('<ul>'.$bulletedList.'</ul>');
        $mailMessage->line('Players are more than welcome to try out Bible Bowl for a brief period.  If they try it out and decide not to play, please login and mark them as "Inactive" in your <a href="'.url('roster').'">player roster</a> to avoid future emails.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
