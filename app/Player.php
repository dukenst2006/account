<?php namespace BibleBowl;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;

/**
 * BibleBowl\Player
 *
 * @property integer $id
 * @property string $guid
 * @property integer $guardian_id
 * @property string $first_name
 * @property string $last_name
 * @property string $shirt_size
 * @property string $gender
 * @property string $birthday
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $guardian
 * @property-read \Illuminate\Database\Eloquent\Collection|Season[] $seasons
 * @property-read mixed $full_name
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereGuardianId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Program[] $programs
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player notRegisteredWithNBB($season, $user)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player registeredWithNBBOnly($season)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player registeredWithGroup($season, $group)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player active($season)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player inactive($season)
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $teams
 */
class Player extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'guid'];

    protected $appends = ['full_name'];

    protected $hidden = ['id','first_name','last_name'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($player) {
            $player->guid = Uuid::uuid4();
            return true;
        });
    }

    public static function validationRules()
    {
        return [
            'first_name'	=> 'required|max:32',
            'last_name'		=> 'required|max:32',
            'gender'		=> 'required',
            'birthday'		=> 'required|date'
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'player_season')
            ->withPivot('program_id', 'group_id', 'grade', 'shirt_size')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'player_season')
            ->withPivot('program_id', 'season_id', 'grade', 'shirt_size')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'player_season')
            ->withPivot('group_id', 'season_id', 'grade', 'shirt_size')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_player')
            ->withTimestamps();
    }

    /**
     * @return null|int
     */
    public function age()
    {
        if (!is_null($this->birthday)) {
            return $this->birthday->age;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $birthday
     */
    public function setBirthdayAttribute($birthday)
    {
        $this->attributes['birthday'] = Carbon::createFromFormat('m/d/Y', $birthday);
    }

    /**
     * Provide birthday as a Carbon object
     *
     * @param $birthday
     *
     * @return static
     */
    public function getBirthdayAttribute($birthday)
    {
        return Carbon::createFromFormat('Y-m-d', $birthday);
    }

    /**
     * If the current player has registered with National Bible Bowl
     *
     * @param Season $season
     *
     * @return bool
     */
    public function isRegisteredWithNBB(Season $season)
    {
        return $this->seasons()->where('seasons.id', $season->id)->count() > 0;
    }

    /**
     * Get the registration data for this player
     *
     * @param Season $season
     *
     * @return object
     */
    public function registration(Season $season)
    {
        return $this->seasons()->wherePivot('season_id', $season->id)->first()->pivot;
    }

    /**
     * Group the current player is registered with
     *
     * @param Season $season
     *
     * @return Group
     */
    public function groupRegisteredWith(Season $season)
    {
        return $this->groups()->wherePivot('season_id', $season->id)->first();
    }

    /**
     * If the current player has registered with a group
     *
     * @param Season $season
     *
     * @return bool
     */
    public function isRegisteredWithGroup(Season $season)
    {
        return $this->groups()->wherePivot('season_id', $season->id)->count() > 0;
    }

    public function scopeNotOnTeamSet(Builder $query, TeamSet $teamSet)
    {
        return $query->whereDoesntHave('teams',
            function (Builder $query) use ($teamSet) {
                $query->where('teams.team_set_id', $teamSet->id);
            });
    }

    public function scopeNotRegisteredWithNBB(Builder $query, Season $season, User $user)
    {
        return $query->where('players.guardian_id', $user->id)
            ->whereDoesntHave('seasons',
                function (Builder $query) use ($season) {
                    $query->where('player_season.season_id', $season->id);
                });
    }

    public function scopeRegisteredWithNBBOnly(Builder $query, Season $season)
    {
        return $query->whereHas('seasons',
            function (Builder $query) use ($season) {
                $query->where('player_season.season_id', $season->id);
                $query->whereNull('player_season.group_id');
            });
    }

    public function scopeRegisteredWithGroup(Builder $query, Season $season, Group $group)
    {
        return $query->whereHas('seasons',
            function (Builder $query) use ($season, $group) {
                $query->where('player_season.season_id', $season->id);
                $query->where('player_season.group_id', $group->id);
            });
    }

    public function deactivate (Season $season) {
        $season->players()
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($this->id, [
                'inactive' => Carbon::now()->toDateTimeString()
            ]);
    }

    public function activate (Season $season) {
        $season->players()
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($this->id, [
                'inactive' => null
            ]);
    }

    /**
     * Query scope for active groups.
     */
    public function scopeActive($query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
                $q->where('seasons.id', $season->id);
            })
            ->whereNull('player_season.inactive');
    }

    /**
     * Query scope for inactive groups.
     */
    public function scopeInactive($query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
                $q->where('seasons.id', $season->id);
            })
            ->whereNotNull('player_season.inactive');
    }

}
