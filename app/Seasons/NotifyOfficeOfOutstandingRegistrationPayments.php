<?php

namespace App\Seasons;

use App\Group;
use App\Season;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Log;
use Mail;

class NotifyOfficeOfOutstandingRegistrationPayments extends Command
{
    const COMMAND = 'biblebowl:notify-office-of-outstanding-registration-payments';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = self::COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a summary email to the office containing details of who has outstanding fees';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $notifyOfficeOfOutstandingPaymentsAfter = config('biblebowl.reminders.notify-office-of-outstanding-registration-payments-after');
        $relativeTime = $notifyOfficeOfOutstandingPaymentsAfter.' ago';
        $playersRegistrationUnpaidSince = new Carbon($relativeTime);
        $groupCount = Group::hasPendingRegistrationPayments(Season::current()->first(), $playersRegistrationUnpaidSince)->count();
        if ($groupCount > 0) {
            Mail::to(config('biblebowl.officeEmail'))->queue(new OutstandingSeasonalRegistrationFeeReport($groupCount));
        } else {
            Log::info('No groups have registration fees older than '.$relativeTime);
        }
    }
}
