<?php

namespace App;

use App\Http\Requests\Request;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\JoinClause;
use Ramsey\Uuid\Uuid;
use Setting;
use Validator;

/**
 * App\Player.
 *
 * @property int $id
 * @property string $guid
 * @property int $guardian_id
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
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereGuardianId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Player whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Program[] $programs
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Player notRegisteredWithNBB($season, $user)
 * @method static \Illuminate\Database\Query\Builder|\App\Player registeredWithNBBOnly($season)
 * @method static \Illuminate\Database\Query\Builder|\App\Player registeredWithGroup($season, $group)
 * @method static \Illuminate\Database\Query\Builder|\App\Player active($season)
 * @method static \Illuminate\Database\Query\Builder|\App\Player inactive($season)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $teams
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Player notOnTeamSet($teamSet)
 * @method static \Illuminate\Database\Query\Builder|\App\Player pendingRegistrationPayment()
 * @method static \Illuminate\Database\Query\Builder|\App\Player notRegistered($season, $user)
 * @mixin \Eloquent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TeamSet[] $teamSet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Player achievedMemoryMaster(\App\Season $season)
 * @method static \Illuminate\Database\Query\Builder|\App\Player haveNotAchievedMemoryMaster(\App\Season $season)
 * @method static \Illuminate\Database\Query\Builder|\App\Player onTeamSet(\App\TeamSet $teamSet)
 * @method static \Illuminate\Database\Query\Builder|\App\Player search(\App\Http\Requests\Request $input)
 * @method static \Illuminate\Database\Query\Builder|\App\Player withSeasonCount()
 * @method static \Illuminate\Database\Query\Builder|\App\Player withUnpaidRegistration(\App\Tournament $tournament)
 * @method static \Illuminate\Database\Query\Builder|\App\Player withoutPendingPayment()
 */
class Player extends Model
{
    const REGISTRATION_SKU = 'TOURNAMENT_REG_PLAYER';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'guid'];

    protected $appends = ['full_name'];

    protected $hidden = ['id'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($player) {
            $player->guid = Uuid::uuid4();

            return true;
        });

        static::deleting(function ($player) {
            $guardian = $player->guardian;

            // if it's the last player
            if ($guardian->players()->count() == 1 && $guardian->isAn(Role::GUARDIAN)) {
                $role = Role::where('name', Role::GUARDIAN)->firstOrFail();
                $guardian->retract($role);
            }
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
                'last_name'     => $data['last_name'],
            ];

            return Player::where($conditions)->count() == 0;
        });

        return [
            'first_name'    => 'required|max:32',
            'last_name'     => 'required|max:32',
            'gender'        => 'required',
            'birthday'      => 'required|date',
        ];
    }

    public static function validationMessages()
    {
        return [
            'first_name.guardian_isnt_duplicate_player' => "You've already added this player",
        ];
    }

    /**
     * Determine if a player's birthday can be edited.
     */
    public function isBirthdayEditable(User $user) : bool
    {
        // some people can always edit it
        if ($user->isA(Role::ADMIN)) {
            return true;
        }

        // can only be edited by guardian for a time period
        if ($this->created_at->gte(Carbon::now()->subMonths(4))) {
            return true;
        }

        return $this->seasons()->count() >= 1;
    }

    public function scopeSearch(Builder $q, Request $input) : Builder
    {
        $q->where('first_name', 'LIKE', '%'.$input->get('q').'%')
            ->orWhere('last_name', 'LIKE', '%'.$input->get('q').'%')
            ->orWhereHas('guardian', function ($q) use ($input) {
                $q->where('users.first_name', 'LIKE', '%'.$input->get('q').'%')
                    ->orWhere('users.last_name', 'LIKE', '%'.$input->get('q').'%')
                    ->orWhere('email', 'LIKE', '%'.$input->get('q').'%');
            });

        return $q;
    }

    public function scopeWithSeasonCount(Builder $q) : Builder
    {
        return $q
            ->addSelect('players.*')
            ->selectRaw('COUNT(player_season.season_id) as seasonCount')
            ->leftJoin('player_season', 'player_season.player_id', '=', 'players.id')
            ->groupBy('players.id');
    }

    public function guardian() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teamSet() : HasManyThrough
    {
        return $this->hasManyThrough(TeamSet::class, Team::class);
    }

    public function events() : BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withPivot('receipt_id')
            ->withTimestamps();
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class)
            ->withPivot('receipt_id')
            ->withTimestamps();
    }

    public function seasons() : BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size', 'memory_master')
            ->withTimestamps();
    }

    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'player_season')
            ->withPivot('season_id', 'grade', 'shirt_size', 'memory_master')
            ->withTimestamps();
    }

    public function teams() : BelongsToMany
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
    }

    public function setFirstNameAttribute($attribute)
    {
        $this->attributes['first_name'] = ucwords(strtolower(trim($attribute)));
    }

    public function setLastNameAttribute($attribute)
    {
        $this->attributes['last_name'] = ucwords(strtolower(trim($attribute)));
    }

    public function getFullNameAttribute() : string
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving.
     *
     * @param $birthday
     */
    public function setBirthdayAttribute($birthday)
    {
        $this->attributes['birthday'] = Carbon::createFromFormat('m/d/Y', $birthday);
    }

    /**
     * Provide birthday as a Carbon object.
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

    public function scopeWithoutPendingPayment(Builder $query)
    {
        if (Setting::firstYearDiscount() == 100) {
            $query->orWhere(function ($q) {
                $q->has('seasons', '=', 1)
                    ->whereNull('player_season.paid');
            });
        }

        return $query->whereNotNull('player_season.paid');
    }

    public function scopePendingRegistrationPayment(Builder $query, Season $season)
    {
        if (Setting::firstYearDiscount() == 100) {
            $query->has('seasons', '>', 1);
        }

        return $query->whereHas('seasons', function ($q) {
            $q->whereNull('player_season.paid');
        })
            ->active($season);
    }

    public function scopeAchievedMemoryMaster(Builder $query, Season $season, int $programId = null)
    {
        $query->whereHas('seasons', function (Builder $query) use ($season) {
            $query
                ->where('seasons.id', $season->id)
                ->where('player_season.memory_master', 1);
        });

        if ($programId != null) {
            $query->whereHas('groups', function (Builder $query) use ($season, $programId) {
                $query
                    ->where('groups.program_id', $programId)
                    ->where('player_season.season_id', $season->id)
                    ->where('player_season.memory_master', 1);
            });
        }
    }

    public function scopeHaveNotAchievedMemoryMaster(Builder $query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $query) use ($season) {
            $query
                ->where('seasons.id', $season->id)
                ->where('player_season.memory_master', 0);
        });
    }

    public function scopeWithUnpaidRegistration(Builder $query, Tournament $tournament)
    {
        return $query->join('tournament_players', function (JoinClause $join) use ($tournament) {
            $join->on('tournament_id', '=', DB::raw($tournament->id))
                ->on('player_id', '=', 'players.id');
        })->whereNull('tournament_players.receipt_id');
    }

    public function scopeWithPaidRegistration(Builder $query, Tournament $tournament)
    {
        return $query->join('tournament_players', function (JoinClause $join) use ($tournament) {
            $join->on('tournament_players.tournament_id', '=', DB::raw($tournament->id))
                ->on('tournament_players.player_id', '=', 'players.id');
        })->whereNotNull('tournament_players.receipt_id');
    }

    /**
     * Group the current player is registered with.
     *
     * @param Season $season
     *
     * @return Group
     */
    public function groupRegisteredWith(Season $season)
    {
        return $this->groups()->wherePivot('season_id', $season->id)->first();
    }

    public function isRegisteredWithGroup(Season $season) : bool
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

    public function scopeOnTeamSet(Builder $query, TeamSet $teamSet)
    {
        return $query->whereHas(
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
                'inactive' => Carbon::now()->toDateTimeString(),
            ]);
    }

    public function activate(Season $season)
    {
        $season->players()
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($this->id, [
                'inactive' => null,
            ]);
    }

    public function scopeActive($query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
            $q->where('seasons.id', $season->id)
                ->whereNull('player_season.inactive');
        });
    }

    public function scopeInactive($query, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
            $q->where('seasons.id', $season->id)
                ->whereNotNull('player_season.inactive');
        });
    }
}
