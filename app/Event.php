<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Event
 *
 * @property integer $id
 * @property integer $tournament_id
 * @property integer $event_type_id
 * @property float $price_per_participant
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Tournament $tournament
 * @property-read EventType $type
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereEventTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event wherePricePerParticipant($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereUpdatedAt($value)
 */
class Event extends Model
{

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament() {
        return $this->belongsTo(Tournament::class);
    }

    public function setPricePerParticipantAttribute($price)
    {
        if ($price = '' || intval($price) == 0) {
            $this->attributes['price_per_participant'] = null;
        } else {
            $this->attributes['price_per_participant'] = $price;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type() {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function isFree()
    {
        return is_null($this->price_per_participant);
    }

    public function displayPrice()
    {
        if($this->isFree()) {
            echo '-';
        } else {
            // Display cost without the ".00"
            $pieces = explode('.', (string)$this->price_per_participant);
            if (isset($pieces[1]) && $pieces[1] > 0) {
                $price = money_format('%.2n', $this->price_per_participant);
            } else {
                $price = number_format($this->price_per_participant);
            }

            echo '$'. $price.' / '.$this->type->participant_type;
        }

    }
}
