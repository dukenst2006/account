<?php

namespace BibleBowl\Competition\Tournaments;

use BibleBowl\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CanBeRegisteredByHeadCoach
{
    public function scopeRegisteredByHeadCoach(Builder $q) : Builder
    {
        return $q->whereNotNull('registered_by');
    }

    public function wasRegisteredByHeadCoach() : bool
    {
        return $this->registered_by != null;
    }

    public function hasGroup() : bool
    {
        return $this->group_id !== null;
    }

    public function registeredBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
