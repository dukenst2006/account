<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
