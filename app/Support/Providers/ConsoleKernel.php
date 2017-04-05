<?php

namespace App\Support\Providers;

use App\Competition\Tournaments\Groups\RemindEarlyBirdFeeEnding;
use App\Competition\Tournaments\Groups\RemindRegistrationEnding;
use App\Location\GroupMapUpdater;
use App\Seasons\MemoryMaster\RemindUpcomingMemoryMasterDeadline;
use App\Seasons\NotifyOfficeOfOutstandingRegistrationPayments;
use App\Seasons\RemindGroupsOfPendingRegistrationPayments;
use App\Seasons\SeasonRotator;
use App\Users\CleanupOrphanAccounts;
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
        RemindUpcomingMemoryMasterDeadline::class,
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
            ->dailyAt('5:00')->thenPing('https://hchk.io/c9e89191-f417-44ee-bf88-03739c439f74');

        $schedule->command(GroupMapUpdater::COMMAND)
            ->dailyAt('5:02')->thenPing('https://hchk.io/5e3052eb-7c81-4e0f-aa34-dfcc833ed7c7');
        $schedule->command(CleanupOrphanAccounts::COMMAND)
            ->dailyAt('5:04')->thenPing('https://hchk.io/3c277ce8-e586-40ff-ab30-75a3cce94e80');

        $schedule->command(RemindEarlyBirdFeeEnding::COMMAND)
            ->dailyAt('5:06')->thenPing('https://hchk.io/ddb0ba20-1b4f-48d4-845b-f035ce3c164e');
        $schedule->command(RemindRegistrationEnding::COMMAND)
            ->dailyAt('5:08')->thenPing('https://hchk.io/fb06645d-0fab-4204-a28a-dc3d5b7694d5');
        $schedule->command(RemindUpcomingMemoryMasterDeadline::COMMAND)
            ->dailyAt('5:10')->thenPing('https://hchk.io/57141569-eca7-4630-9018-c221a0636e86');

        // reminders for unpaid seasonal registration fees
        $schedule->command(RemindGroupsOfPendingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5 2 *'); // Oct-May every Tuesday
        $schedule->command(NotifyOfficeOfOutstandingRegistrationPayments::COMMAND)
            ->cron('0 0 * 10,11,12,1,2,3,4,5,6 3 *'); // Oct-Jun every Wednesday

        $schedule->command('backup:clean')->dailyAt('04:30');
        $schedule->command('backup:run')->dailyAt('06:00')
            ->thenPing('https://hchk.io/8d602e0d-8b35-49e3-8c57-7139ad979feb');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
