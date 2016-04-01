<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Seasons\NotifyOfficeOfOutstandingRegistrationPayments;
use BibleBowl\Seasons\SeasonRotator;
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
            ->daily();

        // reminders for unpaid registration fees
        $schedule->command(RemindGroupsOfPendingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5 2 *'); // Oct-May every Tuesday
        $schedule->command(NotifyOfficeOfOutstandingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5,6 3 *'); // Oct-Jun every Wednesday
    }
}
