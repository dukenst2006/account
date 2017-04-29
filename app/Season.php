<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Setting;

/**
 * App\Season.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Player')
 *             ->withPivot('grade[] $players
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Season current()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|TeamSet[] $teamSets
 * @mixin \Eloquent
 */
class Season extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function players() : BelongsToMany
    {
        // if this relation is updated, update Player too
        return $this->belongsToMany(Player::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size', 'memory_master')
            ->withTimestamps()
            ->orderBy('birthday', 'DESC');
    }

    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'player_season')
            ->withPivot('season_id', 'grade', 'shirt_size')
            ->orderBy('name', 'ASC')
            ->groupBy('player_season.group_id');
    }

    public function scopeCurrent($query)
    {
        return $query->orderBy('id', 'desc')->limit(1);
    }

    public function tournaments() : HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    public function teamSets() : HasMany
    {
        return $this->hasMany(TeamSet::class);
    }

    public function start() : Carbon
    {
        return $this->created_at;
    }

    /**
     * When the season ends/ended.
     */
    public function end() : Carbon
    {
        $lastSeason = self::orderBy('id', 'DESC')->first();

        // the end date may change depending on how the admin has configured it
        if ($this->id == $lastSeason->id) {
            return Setting::seasonEnd();
        }

        // use the start date of the next season
        $nextSeason = self::firstOrFail($this->id + 1);

        return $nextSeason->created_at->subSecond(1);
    }
}
