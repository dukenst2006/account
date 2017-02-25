<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BibleBowl\ParticipantFee
 *
 * @property int $id
 * @property int $tournament_id
 * @property int $participant_type_id
 * @property bool $requires_registration
 * @property float $earlybird_fee
 * @property float $fee
 * @property float $onsite_fee
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \BibleBowl\ParticipantType $participantType
 * @property-read \BibleBowl\Tournament $tournament
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee requiresRegistration()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereEarlybirdFee($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereFee($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereOnsiteFee($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereParticipantTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereRequiresRegistration($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\ParticipantFee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ParticipantFee extends Model
{
    protected $guarded = ['id'];

    public function setOnsiteFeeAttribute($onsiteFee)
    {
        if ($onsiteFee == 0) {
            $onsiteFee = null;
        }

        $this->attributes['onsite_fee'] = $onsiteFee;
    }

    public function scopeRequiresRegistration(Builder $q) : Builder
    {
        return $q->where('requires_registration', 1);
    }

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function participantType() : BelongsTo
    {
        return $this->belongsTo(ParticipantType::class);
    }

    public function requiresRegistration() : bool
    {
        return $this->participant_type_id == ParticipantType::PLAYER || $this->requires_registration == 1;
    }

    public function hasEarlybirdFee() : bool
    {
        return $this->earlybird_fee != null;
    }

    public function hasOnsiteFee() : bool
    {
        return $this->onsite_fee != null;
    }

    public function hasFee() : bool
    {
        return $this->fee != null;
    }

    public function hasAnyFees() : bool
    {
        return $this->hasEarlybirdFee() || $this->hasFee() || $this->hasOnsiteFee();
    }
}
