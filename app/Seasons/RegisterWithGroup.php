<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\User;
use DB;

class RegisterWithGroup
{

    /** @var GroupRegistrar */
    protected $groupRegistrar;

    public function __construct(GroupRegistrar $groupRegistrar)
    {
        $this->groupRegistrar = $groupRegistrar;
    }

    public function handle(Season $season, GroupRegistration $registration)
    {
        DB::beginTransaction();

        foreach ($registration->programs() as $program) {
            // a group may not have been specified
            if ($registration->hasGroup($program) === false) {
                continue;
            }

            $playerIds = array_keys($registration->playerInfo($program));
            $group = $registration->group($program);
            $guardian = Player::findOrFail($playerIds[0])->guardian;
            $this->groupRegistrar->register(
                $season,
                $group,
                $guardian,
                $playerIds
            );
        }

        DB::commit();
    }
}
