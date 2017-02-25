<?php

namespace BibleBowl;

use BibleBowl\Competition\Tournaments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BibleBowl\TournamentType.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\TournamentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\TournamentType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\TournamentType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\TournamentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TournamentType extends Model
{
    const NATIONAL = 1;
    const COLLEGE = 2;
    const OTHER = 3;

    public function tournaments() : HasMany
    {
        return $this->hasMany(Tournament::class);
    }
}
