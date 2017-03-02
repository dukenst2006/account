<?php

use App\Seasons\MemoryMaster\RemindUpcomingMemoryMasterDeadline;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
