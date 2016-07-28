<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class ParticipantFee extends Model
{
    protected $guarded = ['id'];

    public function setOnsiteFeeAttribute($onsiteFee)
    {
        if ($onsiteFee  == 0) {
            $onsiteFee = null;
        }

        $this->attributes['onsite_fee'] = $onsiteFee;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function participantType()
    {
        return $this->belongsTo(ParticipantType::class);
    }
}
