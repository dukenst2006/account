<?php

namespace BibleBowl\Location\Maps;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    const GROUPS = 1;
    const LEAGUES = 2;

    protected $connection = 'wordpress';
    protected $table = 'nbwp_create_map';
    protected $primaryKey = 'map_id';
    protected $guarded = ['map_id'];

    public $timestamps = false;

    public function getMapLocationsAttribute($value) : array
    {
        return unserialize($value);
    }

    public function setMapLocationsAttribute(array $value)
    {
        $this->attributes['map_locations'] = serialize($value);
    }

    public function locations() : array
    {
        return Location::whereIn('location_id', $this->map_locations)->get();
    }
}
