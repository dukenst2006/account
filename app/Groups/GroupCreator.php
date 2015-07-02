<?php namespace BibleBowl\Groups;

use BibleBowl\Location\AddressCreator;
use DB;
use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\User;

class GroupCreator
{
    /** @var AddressCreator */
    protected $addressCreator;

    public function __construct(AddressCreator $addressCreator)
    {
        $this->addressCreator = $addressCreator;
    }

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

        // create the address if one wasn't provided
        // relying on validation rules to make sure required stuff is here
        $addressKeys = [
            'address_one',
            'address_two',
            'city',
            'state',
            'zip_code'
        ];
        if (!isset($attributes['address_id'])) {
            $addressAttributes = array_only($addressKeys, $attributes);
            $address = $this->addressCreator->create($addressAttributes);
            $attributes['address_id'] = $address->id;
        }

        // all input without
        $addressKeys[] = 'user_owned_address';
        $group = Group::create(array_except($attributes, $addressKeys));

        // make the owner a head coach if they aren't already
        if (!$owner->hasRole(Role::HEAD_COACH)) {
            $owner->attachRole(Role::HEAD_COACH_ID);
        }

        DB::commit();

        return $group;
    }
}