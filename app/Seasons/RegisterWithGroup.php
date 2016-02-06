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

    public function handle(Season $season, Registration $registration)
    {
        // a group may not have been specified
        if ($registration->hasGroup() === false) {
            return;
        }

        DB::beginTransaction();

        $playerIds = array_keys($registration->players());
        $guardian = Player::findOrFail($playerIds[0])->guardian;
        $this->groupRegistrar->register(
            $season,
            $registration->group(),
            $guardian,
            $playerIds
        );

        DB::commit();
    }
}
