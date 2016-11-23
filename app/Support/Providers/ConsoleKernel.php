<?php

namespace BibleBowl\Support\Providers;

use BibleBowl\Competition\Tournaments\Groups\RemindEarlyBirdFeeEnding;
use BibleBowl\Competition\Tournaments\Groups\RemindRegistrationEnding;
use BibleBowl\Location\GroupMapUpdater;
use BibleBowl\Seasons\NotifyOfficeOfOutstandingRegistrationPayments;
use BibleBowl\Seasons\RemindGroupsOfPendingRegistrationPayments;
use BibleBowl\Seasons\SeasonRotator;
use BibleBowl\Users\CleanupOrphanAccounts;
use Illuminate\Console\Scheduling\Schedule;

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
        GroupMapUpdater::class,
        RemindEarlyBirdFeeEnding::class,
        CleanupOrphanAccounts::class,
        RemindRegistrationEnding::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(SeasonRotator::COMMAND)
            ->daily()->thenPing('https://hchk.io/c9e89191-f417-44ee-bf88-03739c439f74');

        $schedule->command(GroupMapUpdater::COMMAND)
            ->daily()->thenPing('https://hchk.io/5e3052eb-7c81-4e0f-aa34-dfcc833ed7c7');
        $schedule->command(CleanupOrphanAccounts::COMMAND)
            ->daily()->thenPing('https://hchk.io/3c277ce8-e586-40ff-ab30-75a3cce94e80');

        $schedule->command(RemindEarlyBirdFeeEnding::COMMAND)
            ->daily()->thenPing('https://hchk.io/ddb0ba20-1b4f-48d4-845b-f035ce3c164e');
        $schedule->command(RemindRegistrationEnding::COMMAND)
            ->daily()->thenPing('https://hchk.io/fb06645d-0fab-4204-a28a-dc3d5b7694d5');

        // reminders for unpaid seasonal registration fees
        $schedule->command(RemindGroupsOfPendingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5 2 *'); // Oct-May every Tuesday
        $schedule->command(NotifyOfficeOfOutstandingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5,6 3 *'); // Oct-Jun every Wednesday
    }
}
