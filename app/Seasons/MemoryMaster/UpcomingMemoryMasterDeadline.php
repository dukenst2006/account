<?php

namespace BibleBowl\Seasons\MemoryMaster;

use BibleBowl\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Setting;

class UpcomingMemoryMasterDeadline extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        $message = new MailMessage();
        $message->subject('Upcoming Memory Master Deadline - '.Setting::memoryMasterDeadline()->format('F j'));
        $message->greeting('Memory Master Deadline');
        $message->line('The deadline for players to achieve Memory Master is quickly approaching.  Be sure to select the players in your group who have completed the Memory Verse Master chart before <strong>'.Setting::memoryMasterDeadline()->format('F j')."</strong>.");
        $message->action('Manage Memory Master', url('memory-master'));

        return $message;
    }
}
