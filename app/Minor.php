<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Minor.
 *
 * @property int $id
 * @property int $spectator_id
 * @property string $name
 * @property bool $age
 * @property string $shirt_size
 * @property string $gender
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Spectator $spectator
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereSpectatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Minor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
