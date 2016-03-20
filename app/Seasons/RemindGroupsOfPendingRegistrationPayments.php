<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Season;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Mail;
use Config;

class RemindGroupsOfPendingRegistrationPayments extends Command
{

    const COMMAND = 'biblebowl:remind-groups-of-pending-payments';

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
    protected $description = "Send email reminders who have pending player fees";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $playersRegistrationUnpaidSince = new Carbon(Config::get('biblebowl.reminders.remind-groups-of-pending-payments-after').' ago');
        $groups = Group::hasPendingRegistrationPayments($playersRegistrationUnpaidSince)->get();
        foreach ($groups as $group) {
            foreach ($group->users as $user) {
                Mail::queue(
                    'emails.remind-groups-of-pending-payments',
                    [
                        'groupId'   => $group->id
                    ],
                    function (Message $message) use ($group, $user) {
                        $message->to($user->email, $user->full_name)
                            ->subject('Registration Fee Reminder');
                    }
                );
            }
        }
    }
}
