<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class ParticipantFee extends Model
{
    protected $guarded = ['id'];

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
        return $this->hasOne(ParticipantType::class);
    }
}
