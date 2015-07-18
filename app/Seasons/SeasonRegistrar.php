<?php namespace BibleBowl\Seasons;

use BibleBowl\Season;
use DB;

class SeasonRegistrar
{

    /**
     * Register a player for the given season
     *
     * @param Season $season
     * @param array  $playerData
     */
    public function register(Season $season, array $playerData, $groupId)
    {
        DB::beginTransaction();

        foreach ($playerData as $playerId => $seasonData) {

            // link this player to the group
            if (!is_null($groupId)) {
                $seasonData['group_id'] = $groupId;
            }

            $season->players()->attach($playerId, $seasonData);
        }

        DB::commit();
    }
}