<?php

namespace BibleBowl;

use BibleBowl\Competition\Tournaments;
use BibleBowl\Competition\Tournaments\Groups\Registration;
use BibleBowl\Competition\Tournaments\Settings;
use BibleBowl\Presentation\Describer;
use BibleBowl\Support\CanDeactivate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

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
