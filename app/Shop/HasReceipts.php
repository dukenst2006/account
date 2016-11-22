<?php

namespace BibleBowl\Shop;

use Illuminate\Database\Eloquent\Builder;

trait HasReceipts
{
    public function hasPaid() : bool
    {
        return $this->receipt_id != null;
    }

    public function hasntPaid() : bool
    {
        return !$this->hasPaid();
    }

    public function scopePaid(Builder $q)
    {
        $q->whereNotNull('receipt_id');
    }

    public function scopeUnpaid(Builder $q)
    {
        $q->whereNull('receipt_id');
    }
}
