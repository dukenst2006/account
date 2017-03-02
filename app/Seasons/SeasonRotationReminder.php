<?php

namespace App\Seasons;

use App\Program;
use App\Season;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SeasonRotationReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Carbon */
    protected $willRotateOn;

    /** @var string */
    protected $nextSeasonName;

    public function __construct(Carbon $willRotateOn, string $nextSeasonName)
    {
        $this->willRotateOn = $willRotateOn;
        $this->nextSeasonName = $nextSeasonName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('The '.$this->nextSeasonName.' season begins in '.$this->willRotateOn->diff(Carbon::now())->days.' days')
            ->markdown('emails.season-rotate-notification')
            ->with([
                'programs'       => Program::orderBy('name', 'ASC')->get(),
                'willRotateOn'   => $this->willRotateOn->toFormattedDateString(),
                'nextSeasonName' => $this->nextSeasonName,
            ]);
    }
}
