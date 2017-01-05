<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Minor extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tournament_spectator_minors';

    /**
     * The attributes that are guarded against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function spectator() : BelongsTo
    {
        return $this->belongsTo(Spectator::class);
    }
}
