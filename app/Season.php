<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Season
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Player')
 *             ->withPivot('grade[] $players
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season current()
 * @property-read \Illuminate\Database\Eloquent\Collection|TeamSet[] $teamSets
 */
class Season extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players() {
        // if this relation is updated, update Player too
        return $this->belongsToMany(Player::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size')
            ->withTimestamps()
            ->orderBy('birthday', 'DESC');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'player_season')
            ->withPivot('season_id', 'grade', 'shirt_size')
            ->orderBy('name', 'ASC');
    }

    public function scopeCurrent($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments() {
        return $this->hasMany(Tournament::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamSets() {
        return $this->hasMany(TeamSet::class);
    }

}
