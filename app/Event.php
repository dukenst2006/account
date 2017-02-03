<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * BibleBowl\Event.
 *
 * @property int $id
 * @property int $tournament_id
 * @property int $event_type_id
 * @property float $price_per_participant
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Tournament $tournament
 * @property-read EventType $type
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereEventTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event wherePricePerParticipant($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Event whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    protected $guarded = ['id'];

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function players() : BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->withPivot('receipt_id')
            ->withTimestamps();
    }

    public function eligiblePlayers() : BelongsToMany
    {
        if ($this->isFree()) {
            return $this->players();
        }

        return $this->paidPlayers();
    }

    public function scopeRequiringFees(Builder $q) : Builder
    {
        return $q->whereNotNull('price_per_participant');
    }

    public function paidPlayers() : BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->wherePivot('receipt_id', '!=', null)
            ->withPivot('receipt_id')
            ->withTimestamps();
    }

    public function unpaidPlayers() : BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->wherePivot('receipt_id', null)
            ->withPivot('receipt_id')
            ->withTimestamps();
    }

    public function scopeByParticipantType(Builder $builder, int $participantTypeId)
    {
        return $builder->whereHas('type', function (Builder $q) use ($participantTypeId) {
            $q->where('participant_type_id', $participantTypeId);
        });
    }

    public function scopeWithOptionalParticipation(Builder $builder)
    {
        return $builder->where('required', 0);
    }

    public function setPricePerParticipantAttribute($price)
    {
        if ($price == '' || intval($price) == 0) {
            $this->attributes['price_per_participant'] = null;
        } else {
            $this->attributes['price_per_participant'] = $price;
        }
    }

    public function getPricePerParticipantAttribute()
    {
        if (is_null($this->attributes['price_per_participant'])) {
            return;
        }

        return money_format('%.2n', $this->attributes['price_per_participant']);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function isFree() : bool
    {
        return is_null($this->price_per_participant);
    }

    public function hasFee() : bool
    {
        return is_null($this->price_per_participant) === false;
    }

    public function isParticipationOptional() : bool
    {
        return $this->type->participant_type_id == ParticipantType::PLAYER && $this->required == 0;
    }

    public function displayPrice()
    {
        if ($this->isFree()) {
            echo '-';
        } else {
            // Display cost without the ".00"
            $pieces = explode('.', (string) $this->price_per_participant);
            if (isset($pieces[1]) && $pieces[1] > 0) {
                $price = money_format('%.2n', $this->price_per_participant);
            } else {
                $price = number_format($this->price_per_participant);
            }

            echo '$'.$price.' / '.$this->type->participantType->name;
        }
    }

    public function sku() : string
    {
        return 'TOURNAMENT_REG_EVENT_'.$this->event_type_id;
    }
}
