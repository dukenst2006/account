<?php namespace BibleBowl\Groups;

use Bouncer;
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
        $group->addHeadCoach($owner);

        DB::commit();

        return $group;
    }
}
