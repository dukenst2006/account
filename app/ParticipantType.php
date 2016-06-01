<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class ParticipantType extends Model
{
    const TEAM = 1;
    const PLAYER = 2;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function participantFee()
    {
        return $this->belongsTo(ParticipantFee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
