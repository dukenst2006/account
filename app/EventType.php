<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    const PARTICIPANT_TEAM = 'team';
    const PARTICIPANT_PLAYER = 'player';

    protected $guarded = ['id'];

    public static function validationRules()
    {
        return [
            'tournament_id'         => 'required',
            'event_type_id'	        => 'required',
            'price_per_participant'	=> 'numeric'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events() {
        return $this->hasMany(Event::class);
    }

    public function setPricePerParticipantAttribute($pricePerParticipant)
    {
        $pricePerParticipant = floatval($pricePerParticipant);

        // default to null, force float if a value was provided
        $price = null;
        if ($pricePerParticipant > 0) {
            $price = $pricePerParticipant;
        }

        $this->attributes['price_per_participant'] = $price;
    }
}
