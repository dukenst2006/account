<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Season;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Log;
use Mail;
use Config;
use Carbon\Carbon;

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
    protected $description = "Send a summary email to the Bible Bowl office containing details of who has outstanding fees";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $relativeTime = Config::get('biblebowl.reminders.notify-office-of-outstanding-registration-payments-after').' ago';
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