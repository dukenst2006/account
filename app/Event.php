<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tournament() {
        return $this->hasOne(Tournament::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type() {
        return $this->hasOne(EventType::class);
    }
}
