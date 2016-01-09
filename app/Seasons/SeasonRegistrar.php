<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Program;
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
    public function register(Season $season, $groupId, User $guardian, Program $program, array $playerData)
    {
        DB::beginTransaction();

        foreach ($playerData as $playerId => $seasonData) {
            $seasonData['program_id'] = $program->id;

            $season->players()->attach($playerId, $seasonData);
        }

        // register the players with the group
        if (!is_null($groupId)) {
            $group = Group::findOrFail($groupId);
            $this->groupRegistrar->register($season, $group, $guardian, array_keys($playerData));
        }

        DB::commit();
    }
}
