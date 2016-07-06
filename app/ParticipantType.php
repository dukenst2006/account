<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class ParticipantType extends Model
{
    const TEAM = 1;
    const PLAYER = 2;
    const QUIZMASTER = 3;
    const ADULT = 4;
    const FAMILY = 5;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participantFee()
    {
        return $this->hasMany(ParticipantFee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
