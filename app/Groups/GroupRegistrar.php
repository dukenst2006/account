<?php namespace BibleBowl\Groups;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\User;
use DB;

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