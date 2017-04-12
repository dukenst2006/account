<?php

namespace App\Groups;

use App\Group;
use App\User;
use DB;

class GroupCreator
{
    /**
     * @param User  $owner
     * @param array $attributes
     *
     * @return static
     */
    public function create(User $owner, array $attributes) : Group
    {
        $attributes['owner_id'] = $owner->id;

        DB::beginTransaction();

        $group = Group::create($attributes);
        $group->addHeadCoach($owner);

        DB::commit();

        return $group;
    }
}
