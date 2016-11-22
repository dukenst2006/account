<?php

namespace BibleBowl\Competition\Teams;

use BibleBowl\TeamSet;
use DB;

class Duplicater
{
    /**
     * Duplicate a set of teams.
     */
    public function duplicate(TeamSet $teamSet, array $except = null) : TeamSet
    {
        DB::beginTransaction();

        $newTeamSet = $teamSet->replicate($except);
        $newTeamSet->save();
        foreach ($teamSet->teams()->with('players')->get() as $team) {
            $newTeam = $team->replicate([
                'team_set_id',
                'receipt_id', // nullify any "paid" status associated with this team
            ]);
            $newTeamSet->teams()->save($newTeam);
            $newTeam->players()->sync($team->players->modelKeys());
        }

        DB::commit();

        return $newTeamSet;
    }
}
