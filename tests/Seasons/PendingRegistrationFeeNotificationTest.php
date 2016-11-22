<?php

use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\Seasons\RemindGroupsOfPendingRegistrationPayments;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PendingRegistrationFeeNotificationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function sendsToGroupsWithPlayersOutsideOfPendingWindow()
    {
        $playersRegistrationUnpaidSince = new Carbon(config('biblebowl.reminders.remind-groups-of-pending-payments-after').' ago');
        $season = Season::current()->first();

        $groupId = 2; // Mount Pleasant
        DB::statement('UPDATE player_season SET created_at = ?, paid = NULL WHERE group_id = ? AND season_id = ?', [
            $playersRegistrationUnpaidSince->copy()->addDays(3)->toDateTimeString(),
            $groupId,
            $season->id,
        ]);

        $this->assertEquals($groupId, Group::hasPendingRegistrationPayments($season, $playersRegistrationUnpaidSince)->first()->id);

        Mail::shouldReceive('queue')->once();

        Artisan::call(RemindGroupsOfPendingRegistrationPayments::COMMAND);
    }

    /** @test */
    public function doesntSendToGroupsWithPlayersInsideOfPendingWindow()
    {
        $playersRegistrationUnpaidSince = new Carbon(config('biblebowl.reminders.remind-groups-of-pending-payments-after').' ago');
        $season = Season::current()->first();

        $groupId = 2; // Mount Pleasant
        DB::statement('UPDATE player_season SET created_at = ?, paid = NULL WHERE group_id = ? AND season_id = ?', [
            $playersRegistrationUnpaidSince->copy()->subDays(3)->toDateTimeString(),
            $groupId,
            $season->id,
        ]);

        $this->assertNull(Group::hasPendingRegistrationPayments($season, $playersRegistrationUnpaidSince)->first());

        Mail::shouldReceive('queue')->never();

        Artisan::call(RemindGroupsOfPendingRegistrationPayments::COMMAND);
    }
}
