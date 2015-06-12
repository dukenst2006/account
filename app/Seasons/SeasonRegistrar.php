<?php namespace BibleBowl\Seasons;

use BibleBowl\Season;
use DB;

class SeasonRegistrar
{

    /**
     * Register a player for the given season
     *
     * @param Season $season
     * @param array  $players
     */
    public function register(Season $season, array $playerData)
    {
        DB::beginTransaction();

        foreach ($playerData as $playerId => $seasonData) {
            $season->players()->attach($playerId, $seasonData);
        }

        DB::commit();
    }
}