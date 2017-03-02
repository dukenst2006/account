<?php

namespace App\Groups;

use App\Group;
use App\Season;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeactivatedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Group */
    protected $group;

    /** @var Season */
    protected $season;

    public function __construct(Group $group, Season $season)
    {
        $this->group = $group;
        $this->season = $season;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->group->name.' Automatically Deactivated')
            ->markdown('emails.inactive-group-notification')
            ->with([
                'group'  => $this->group,
                'season' => $this->season,
            ]);
    }
}
