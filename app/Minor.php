<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BibleBowl\Minor.
 *
 * @property int $id
 * @property int $spectator_id
 * @property string $name
 * @property bool $age
 * @property string $shirt_size
 * @property string $gender
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \BibleBowl\Spectator $spectator
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereAge($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereSpectatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Minor whereUpdatedAt($value)
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
