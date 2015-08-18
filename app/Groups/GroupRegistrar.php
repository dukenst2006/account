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

        $season->players()
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($playerIds, [
                'group_id' => $group->id
            ]);

        DB::commit();

        return $group;
    }
}