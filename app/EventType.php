<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\EventType
 *
 * @property integer $id
 * @property string $participant_type
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $events
 * @property-write mixed $price_per_participant
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\EventType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\EventType whereParticipantType($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\EventType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\EventType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\EventType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventType extends Model
{
    const ROUND_ROBIN = 1;
    const QUOTE_BEE = 2;
    const DOUBLE_ELIMINATION = 3;
    const BUZZ_OFF = 4;
    const KING_OF_THE_HILL = 5;

    protected $guarded = ['id'];

    public static function validationRules()
    {
        return [
            'tournament_id'             => 'required',
            'event_type_id'             => 'required',
            'price_per_participant'     => 'numeric'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function participantType()
    {
        return $this->belongsTo(ParticipantType::class);
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
