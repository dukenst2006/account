<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Seasons\NotifyOfficeOfOutstandingRegistrationPayments;
use BibleBowl\Seasons\SeasonRotator;
use BibleBowl\Users\Auth\EmailConfirmationReminder;
use Illuminate\Console\Scheduling\Schedule;
use BibleBowl\Seasons\RemindGroupsOfPendingRegistrationPayments;

class ConsoleKernel extends \Illuminate\Foundation\Console\Kernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SeasonRotator::class,
        RemindGroupsOfPendingRegistrationPayments::class,
        NotifyOfficeOfOutstandingRegistrationPayments::class,
        //EmailConfirmationReminder::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command(SeasonRotator::COMMAND)
            ->daily()->thenPing('https://hchk.io/c9e89191-f417-44ee-bf88-03739c439f74');

//        $schedule->command(EmailConfirmationReminder::COMMAND)
//            ->daily()->thenPing('https://hchk.io/5e3052eb-7c81-4e0f-aa34-dfcc833ed7c7');

        // reminders for unpaid registration fees
        $schedule->command(RemindGroupsOfPendingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5 2 *'); // Oct-May every Tuesday
        $schedule->command(NotifyOfficeOfOutstandingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5,6 3 *'); // Oct-Jun every Wednesday
    }
}
