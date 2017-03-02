<?php

namespace App\Groups;

use App\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeactivatedSummary extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var array */
    protected $groupIds;

    public function __construct(array $groupIds)
    {
        $this->groupIds = $groupIds;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Group'.(count($this->groupIds) > 1 ? 's' : '').' Automatically Deactivated')
            ->markdown('emails.inactive-group-summary')
            ->with([
                'groupIds' => $this->groupIds,
            ]);
    }
}
