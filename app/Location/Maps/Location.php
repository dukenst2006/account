<?php

namespace BibleBowl\Location\Maps;

use BibleBowl\Group;
use BibleBowl\Program;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    const TEEN_GROUPS_MARKER = 1;
    const BEGINNER_GROUPS_MARKER = 2;
    const TEEN_LEAGUES_MARKER = 3;
    const BEGINNER_LEAGUES_MARKER = 4;

    protected $connection = 'wordpress';
    protected $table = 'nbwp_map_locations';
    protected $primaryKey = 'location_id';
    protected $guarded = ['location_id'];

    public $timestamps = false;

    // default values to what they're defaulted by the wordpress plugin
    protected $attributes = [
        'location_settings'     => 'a:3:{s:7:"onclick";s:6:"marker";s:13:"redirect_link";s:0:"";s:20:"redirect_link_window";s:3:"yes";}',
        'location_group_map'    => 'a:1:{i:0;s:1:"'.Map::GROUPS.'";}',
        'location_animation'    => 'BOUNCE',
        'location_author'       => '4', // bkuhl
        'location_country'      => 'United States',
    ];

    public function updateMarkerInformation(Group $group)
    {
        $this->location_extrafields = [
            'guid' => $group->guid
        ];

        if ($group->program_id == Program::TEEN) {
            $this->location_group_map = self::TEEN_GROUPS_MARKER;
        } else {
            $this->location_group_map = self::BEGINNER_GROUPS_MARKER;
        }

        $this->location_title = $group->name;
        $this->location_address = $group->meetingAddress.'';
        $this->location_city = $group->meetingAddress->city;
        $this->location_state = $group->meetingAddress->state;
        $this->location_postal_code = $group->meetingAddress->zip_code;
        $this->location_latitude = $group->meetingAddress->latitude;
        $this->location_longitude = $group->meetingAddress->longitude;

        $this->location_messages = view('wordpress-map-tooltip', [
                'group' => $group
            ]).'';
    }

    public function getLocationExtrafieldsAttribute($value) : array
    {
        return unserialize($value);
    }

    public function setLocationExtrafieldsAttribute(array $value)
    {
        $this->attributes['location_extrafields'] = serialize($value);
    }

    public function getLocationGroupMapAttribute($value) : array
    {
        return unserialize($value);
    }

    public function setLocationGroupMapAttribute($markerId)
    {
        $this->attributes['location_group_map'] = serialize([
            $markerId
        ]);
    }
}
