<?php

namespace BibleBowl;

use BibleBowl\Competition\Tournaments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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
