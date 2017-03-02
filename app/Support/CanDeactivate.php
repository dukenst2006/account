<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait CanDeactivate
{
    /**
     * Sets the active/inactive states.
     *
     * @param $attribute
     */
    public function setInactiveAttribute($attribute)
    {
        $inactiveColumn = $this->getInactiveColumn();

        // strip table name
        if (str_contains($inactiveColumn, '.')) {
            $inactiveColumn = explode('.', $inactiveColumn)[1];
        }

        if ($attribute == 1) {
            // Only save a new timestamp if one isn't already set.
            if (is_null($this->attributes[$inactiveColumn])) {
                $this->attributes[$inactiveColumn] = Carbon::now()->toDateTimeString();
            }
        } else {
            $this->attributes[$inactiveColumn] = null;
        }
    }

    /**
     * Query scope for active groups.
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereNull($this->getInactiveColumn());
    }

    /**
     * Query scope for inactive groups.
     */
    public function scopeInactive(Builder $query)
    {
        return $query->whereNotNull($this->getInactiveColumn());
    }

    public function isActive() : bool
    {
        return is_null($this->inactive) ? true : false;
    }

    public function isInactive() : bool
    {
        return !$this->isActive();
    }

    /**
     * @return string
     */
    public function getInactiveColumn()
    {
        return $this->getTable().'.inactive';
    }
}
