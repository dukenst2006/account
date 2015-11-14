<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    const TEAM = 'team';
    const PLAYER = 'player';

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events() {
        return $this->hasMany(Event::class);
    }
}
