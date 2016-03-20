<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Session;

class RecordSeasonalRegistrationPayment
{
    /**
     * @param Player[] $players
     */
    public function handle(Collection $players)
    {
        $playerIds = $players->pluck('id')->toArray();
        $now = Carbon::now();
        DB::update('UPDATE player_season SET paid = ? WHERE group_id = ? AND player_id IN('.implode(',', $playerIds).')', [
            $now->toDateTimeString(),
            Session::group()->id
        ]);
    }
}
