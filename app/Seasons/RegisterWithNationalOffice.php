<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\User;
use DB;

class RegisterWithNationalOffice
{

    public function handle(Season $season, GroupRegistration $registration)
    {
        DB::beginTransaction();

        foreach ($registration->programs() as $program)
        {
            foreach ($registration->playerInfo($program) as $playerId => $seasonData) {
                $season->players()->attach($playerId, $seasonData);
            }
        }

        DB::commit();
    }
}
