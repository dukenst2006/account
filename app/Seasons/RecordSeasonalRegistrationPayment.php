<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\User;
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
        DB::update('UPDATE player_season SET paid = 1 WHERE group_id = ? AND player_id IN('.implode(',', $playerIds).')', [
            Session::group()->id
        ]);
    }
}