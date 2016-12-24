<?php

use BibleBowl\Seasons\SeasonRotator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use BibleBowl\Seasons\MemoryMaster\RemindUpcomingMemoryMasterDeadline;

class RemindUpcomingMemoryMasterDeadlineTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function sendsMemoryMasterDeadlineNotifications()
    {
        Carbon::setTestNow(Setting::memoryMasterDeadline()->subDays(7));

        Artisan::call(RemindUpcomingMemoryMasterDeadline::COMMAND);

        Carbon::setTestNow();
    }
}
