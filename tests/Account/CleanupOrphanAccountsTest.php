<?php

use App\User;
use App\Users\CleanupOrphanAccounts;
use Carbon\Carbon;

class CleanupOrphanAccountsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function onlyDeletesAccountsRequiringSetup()
    {
        $this->cleanupAfter = config('biblebowl.cleanup-orphan-accounts-after');
        $orphanedFor = Carbon::now()->subDays($this->cleanupAfter);
        $userCount = User::count();
        $shouldBeDeletedCount = User::where('created_at', '<', $orphanedFor)->requiresSetup()->count();
        $this->assertGreaterThan(0, $shouldBeDeletedCount);

        $this->artisan(CleanupOrphanAccounts::COMMAND);

        $this->assertEquals(0, User::where('created_at', '<', $orphanedFor)->requiresSetup()->count());
        $this->assertEquals($userCount - $shouldBeDeletedCount, User::count());
    }
}
