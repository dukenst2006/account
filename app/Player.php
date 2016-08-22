<?php namespace BibleBowl;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;
use Validator;

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
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player notOnTeamSet($teamSet)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player pendingRegistrationPayment()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Player notRegistered($season, $user)
 * @mixin \Eloquent
 */
class Player extends Model
{

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
        // check to see if the current user already has a player with this same name
        // Admins can add duplicate players, but parents can't
        Validator::extend('guardian_isnt_duplicate_player', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $conditions = [
                'guardian_id'   => Auth::user()->id,
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name']
            ];
            return Player::where($conditions)->count() == 0;
        });

        return [
            'first_name'    => 'required|max:32',
            'last_name'     => 'required|max:32',
            'gender'        => 'required',
            'birthday'      => 'required|date'
        ];
    }

    public static function validationMessages()
    {
        return [
            'first_name.guardian_isnt_duplicate_player' => "You've already added this player"
        ];
    }

    /**
     * Determine if a player's birthday can be edited
     *
     * @param User $user
     * @return bool
     */
    public function isBirthdayEditable(User $user)
    {
        // some people can always edit it
        if ($user->is(Role::ADMIN)) {
            return true;
        }

        // can only be edited by guardian for a time period
        if ($this->created_at->gte(Carbon::now()->subMonths(4))) {
            return true;
        }

        return $this->seasons()->count() >= 1;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function teamSet()
    {
        return $this->hasManyThrough(Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'player_season')
            ->withPivot('season_id', 'grade', 'shirt_size', 'inactive')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function programs()
    {
        return $this->hasManyThrough(Program::class, Group::class, 'player_season')
            ->withPivot('group_id', 'season_id', 'grade', 'shirt_size')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_player')
            ->withPivot('order')
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

    public function setFirstNameAttribute($attribute)
    {
        $this->attributes['first_name'] = ucwords(strtolower(trim($attribute)));
    }

    public function setLastNameAttribute($attribute)
    {
        $this->attributes['last_name'] = ucwords(strtolower(trim($attribute)));
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
        if (!$birthday instanceof Carbon) {
            return Carbon::createFromFormat('Y-m-d', $birthday);
        }

        return $birthday;
    }

    public function scopePendingRegistrationPayment(Builder $query, Season $season)
    {
        return $query->whereNull('player_season.paid')
            ->active($season);
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
        return $query->whereDoesntHave(
            'teams',
            function (Builder $query) use ($teamSet) {
                $query->where('teams.team_set_id', $teamSet->id);
            }
        );
    }

    public function scopeNotRegistered(Builder $query, Season $season, User $user)
    {
        return $query->where('players.guardian_id', $user->id)
            ->whereDoesntHave(
                'seasons',
                function (Builder $query) use ($season) {
                    $query->where('player_season.season_id', $season->id);
                }
            );
    }

    public function scopeRegisteredWithGroup(Builder $query, Season $season, Group $group)
    {
        return $query->whereHas(
            'seasons',
            function (Builder $query) use ($season, $group) {
                $query->where('player_season.season_id', $season->id);
                $query->where('player_season.group_id', $group->id);
            }
        );
    }

    public function deactivate(Season $season)
    {
        $season->players()
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($this->id, [
                'inactive' => Carbon::now()->toDateTimeString()
            ]);
    }

    public function activate(Season $season)
    {
        $season->players()
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($this->id, [
                'inactive' => null
            ]);
    }

    /**
     * Query scope for inactive groups.
     */
    public function scopeActive($query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
                $q->where('seasons.id', $season->id);
        })
            ->whereNull('player_season.inactive');
    }

    /**
     * Query scope for inactive players.
     */
    public function scopeInactive($query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
                $q->where('seasons.id', $season->id);
        })
            ->whereNotNull('player_season.inactive');
    }
}
