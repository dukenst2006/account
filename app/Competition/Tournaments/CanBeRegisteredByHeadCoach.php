<?php

namespace BibleBowl\Competition\Tournaments;

use Illuminate\Database\Eloquent\Builder;

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
}
