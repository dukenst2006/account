<?php namespace BibleBowl\Players;

use BibleBowl\Player;
use BibleBowl\Role;
use BibleBowl\User;
use DB;

class PlayerCreator
{
    /**
     * @param User  $guardian
     * @param array $attributes
     *
     * @return static
     */
    public function create(User $guardian, array $attributes)
    {
        $attributes['guardian_id'] = $guardian->id;

        DB::beginTransaction();

        $player = Player::create($attributes);
        if (!$guardian->hasRole(Role::GUARDIAN)) {
            $guardian->attachRole(Role::GUARDIAN_ID);
        }

        DB::commit();

        return $player;
    }
}