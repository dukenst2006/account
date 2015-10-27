<?php namespace BibleBowl\Groups;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\User;
use DB;

class GroupCreator
{
    /**
     * @param User  $owner
     * @param array $attributes
     *
     * @return static
     */
    public function create(User $owner, array $attributes)
    {
        $attributes['owner_id'] = $owner->id;

        DB::beginTransaction();

        $group = Group::create($attributes);
        $owner->groups()->attach($group->id);

        // make the owner a head coach if they aren't already
        if (!$owner->hasRole(Role::HEAD_COACH)) {
            $owner->attachRole(Role::HEAD_COACH_ID);
        }

        DB::commit();

        return $group;
    }
}