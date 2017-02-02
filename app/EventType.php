<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BibleBowl\EventType.
 *
 * @property int $id
 * @property string $participant_type
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $events
 * @property-write mixed $price_per_participant
 *
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
    const WRITTEN_TEST = 6;

    protected $guarded = ['id'];

    public static function validationRules()
    {
        return [
            'tournament_id'             => 'required',
            'event_type_id'             => 'required',
            'price_per_participant'     => 'numeric',
        ];
    }

    public function events() : HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function participantType() : BelongsTo
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
