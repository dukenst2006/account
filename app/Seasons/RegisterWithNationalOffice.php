<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\User;
use DB;

class RegisterWithNationalOffice
{

    public function handle(Season $season, Registration $registration)
    {
        DB::beginTransaction();

        $program = $registration->group()->program;
        foreach ($registration->players() as $playerId => $seasonData) {
            $seasonData['program_id'] = $program->id;

            $season->players()->attach($playerId, $seasonData);
        }

        DB::commit();
    }
}
