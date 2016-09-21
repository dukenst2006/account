<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function spectators()
    {
        return $this->belongsTo(Spectator::class);
    }
}
