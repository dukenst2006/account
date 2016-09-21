<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Season.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Player')
 *             ->withPivot('grade[] $players
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season current()
 *
 * @property int $team_set_id
 * @property-read Group $teamSet
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Team whereTeamSetId($value)
 */
class Team extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamSet()
    {
        return $this->belongsTo(TeamSet::class);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords(strtolower($name));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        $seasonId = $this->teamSet->season_id;

        return $this->belongsToMany(Player::class, 'team_player')
            ->withPivot('order')
            ->withTimestamps()
            ->with(['seasons' => function ($query) use ($seasonId) {
                $query->where('season_id', $seasonId);
            }])
            ->orderBy('team_player.order', 'ASC');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($team) {
            $team->players()->sync([]);
        });
    }
}
