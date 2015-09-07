<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Season;
use BibleBowl\User;
use DB;

class SeasonRegistrar
{

    /** @var GroupRegistrar */
    protected $groupRegistrar;

    public function __construct(GroupRegistrar $groupRegistrar)
    {
        $this->groupRegistrar = $groupRegistrar;
    }

    /**
     * Register a player for the given season
     *
     * @param Season $season
     * @param array  $playerData
     */
    public function register(Season $season, $groupId, User $guardian, array $playerData)
    {
        DB::beginTransaction();

        foreach ($playerData as $playerId => $seasonData) {
            $season->players()->attach($playerId, $seasonData);
        }

        // register the players with the group
        if (!is_null($groupId)) {
            $this->groupRegistrar->register($season, Group::findOrFail($groupId), $guardian, array_keys($playerData));
        }

        DB::commit();
    }
}