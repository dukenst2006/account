<?php namespace BibleBowl\Location;

use BibleBowl\Address;
use DB;

class AddressCreator
{
    /**
     * @param array $attributes
     *
     * @return static
     */
    public function create(array $attributes)
    {
        // coordinates are fetched via model events

        return Address::create($attributes);
    }
}