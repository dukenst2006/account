<?php

namespace App\Location;

use App\Address;

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
