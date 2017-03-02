<?php

namespace App\Seasons;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OutstandingSeasonalRegistrationFeeReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var int */
    protected $groupCount;

    public function __construct(int $groupCount)
    {
        $this->groupCount = $groupCount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Outstanding Registration Fees')
            ->markdown('emails.notify-office-of-outstanding-pending-payments')
            ->with([
                'groupCount'         => $this->groupCount,
                'outstandingAtLeast' => config('biblebowl.reminders.notify-office-of-outstanding-registration-payments-after'),
            ]);
    }
}
