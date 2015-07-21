<?php namespace BibleBowl\Groups;

use BibleBowl\Season;
use BibleBowl\Player;
use Carbon\Carbon;
use DB;
use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\User;

class GroupRegistrar
{

    public function register(Season $season, Group $group, array $playerIds)
    {
        DB::beginTransaction();

        DB::raw('UPDATE player_season SET group_id = ?, updated_at = ? WHERE player_id IN('.implode(',', $playerIds).') AND season_id = ?', [
            $group->id,
            $season->id,
            Carbon::now()->toDateTimeString()
        ]);

        DB::commit();

        return $group;
    }
}