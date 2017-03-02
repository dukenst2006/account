<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\TournamentType.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentType whereUpdatedAt($value)
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
