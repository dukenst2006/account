<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\ParticipantFee.
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
 * @property-read \App\ParticipantType $participantType
 * @property-read \App\Tournament $tournament
 *
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee requiresRegistration()
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereEarlybirdFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereOnsiteFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereParticipantTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereRequiresRegistration($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ParticipantFee whereUpdatedAt($value)
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
