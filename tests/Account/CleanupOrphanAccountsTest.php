<?php

use BibleBowl\User;
use BibleBowl\Users\CleanupOrphanAccounts;
use Carbon\Carbon;

class CleanupOrphanAccountsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function onlyDeletesUnconfirmedAccounts()
    {
        $userCount = User::count();
        $orphanedFor = Carbon::now()->subDays(config('biblebowl.cleanup-orphan-accounts-after'));
        $shouldBeDeletedCount = User::where('status', User::STATUS_UNCONFIRMED)->where('created_at', '>', $orphanedFor)->count();
        $this->assertEquals(5, $shouldBeDeletedCount);

        $this->artisan(CleanupOrphanAccounts::COMMAND);

        $this->assertEquals(1, User::where('status', User::STATUS_UNCONFIRMED)->count());
        $this->assertEquals($userCount - $shouldBeDeletedCount, User::count());
    }
}
