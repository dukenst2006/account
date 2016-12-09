<?php

use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\Seasons\RemindGroupsOfPendingRegistrationPayments;
use BibleBowl\Users\Notifications\RemindPendingSeasonalRegistrationFees;
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

        $group = Group::hasPendingRegistrationPayments($season, $playersRegistrationUnpaidSince)->first();
        $this->assertEquals($groupId, $group->id);

        foreach ($group->users as $user) {
            $this->expectsNotification($user, RemindPendingSeasonalRegistrationFees::class);
        }

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

        Artisan::call(RemindGroupsOfPendingRegistrationPayments::COMMAND);
    }
}
