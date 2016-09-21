<?php

namespace BibleBowl\Seasons;

use BibleBowl\Group;
use Carbon\Carbon;
use Config;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
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
        if (Group::hasPendingRegistrationPayments($playersRegistrationUnpaidSince)->count() > 0) {
            Mail::queue(
                'emails.notify-office-of-outstanding-pending-payments',
                [],
                function (Message $message) {
                    $message->to(Config::get('biblebowl.officeEmail'))
                        ->subject('Outstanding Registration Fees');
                }
            );
        } else {
            Log::info('No groups have registration fees older than '.$relativeTime);
        }
    }
}
