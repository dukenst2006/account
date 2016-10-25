<?php

use BibleBowl\User;

class CleanupOrphanAccountsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function onlyDeletesUnconfirmedAccounts()
    {
        $userCount = User::count();
        $shouldBeDeletedCount = User::where('status', User::STATUS_UNCONFIRMED)->count();
        $this->assertGreaterThan(0, $shouldBeDeletedCount);

        $this->artisan(\BibleBowl\Users\CleanupOrphanAccounts::COMMAND);

        $this->assertEquals(0, User::where('status', User::STATUS_UNCONFIRMED)->count());
        $this->assertEquals($userCount - $shouldBeDeletedCount, User::count());
    }
}
