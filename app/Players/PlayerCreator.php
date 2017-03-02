<?php

namespace App\Players;

use App\Player;
use App\Role;
use App\User;
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
        if ($guardian->isNotA(Role::GUARDIAN)) {
            $role = Role::where('name', Role::GUARDIAN)->firstOrFail();
            $guardian->assign($role);
        }

        DB::commit();

        return $player;
    }
}
