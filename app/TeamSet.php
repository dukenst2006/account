<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @property integer $group_id
 * @property integer $season_id
 * @property-read Group $group
 * @property-read Season $season
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $teams
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\TeamSet whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\TeamSet whereSeasonId($value)
 */
class TeamSet extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function validationRules()
    {
        return [
            'name'  => 'required|max:64'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season() {
        return $this->belongsTo(Season::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams() {
        $seasonId = $this->season_id;
        return $this->hasMany(Team::class)
            ->with([
                'players.seasons' => function (BelongsToMany $query) use ($seasonId) {
                    $query->where('season_id', $seasonId);
                    $query->withPivot('grade');
                }
            ]);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords(strtolower($name));
    }

}
