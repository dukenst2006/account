<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament() {
        return $this->belongsTo(Tournament::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type() {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function isFree()
    {
        return is_null($this->price_per_participant);
    }

    public function displayPrice()
    {
        if($this->isFree()) {
            echo '-';
        } else {
            // Display cost without the ".00"
            $pieces = explode('.', (string)$this->price_per_participant);
            if (isset($pieces[1]) && $pieces[1] > 0) {
                $price = money_format('%.2n', $this->price_per_participant);
            } else {
                $price = number_format($this->price_per_participant);
            }

            echo '$'. $price.' / '.$this->type->participant_type;
        }

    }
}
