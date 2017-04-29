<?php

namespace App;

use App\Groups\GroupOwnershipTransferred;
use App\Groups\Settings;
use App\Location\Maps\Location;
use App\Support\CanDeactivate;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Mail;
use Ramsey\Uuid\Uuid;
use Validator;
use Setting;

/**
 * App\Group.
 *
 * @property int $id
 * @property string $guid
 * @property bool $type
 * @property string $name
 * @property int $owner_id
 * @property int $address_id
 * @property int $meeting_address_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Address $address
 * @property-read mixed $full_name
 * @property-read User $users
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereMeetingAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereUpdatedAt($value)
 * @method static \App\Group near($address, $miles = null)
 *
 * @property int $program_id
 * @property \Carbon\Carbon $inactive
 * @property-read Address $meetingAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read User $owner
 * @property-read Program $program
 * @property-read \Illuminate\Database\Eloquent\Collection|Season[] $seasons
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereProgramId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereInactive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group byProgram($program)
 * @method static \Illuminate\Database\Query\Builder|\App\Group activeGuardians($group, $season)
 * @method static \Illuminate\Database\Query\Builder|\App\Group inactiveGuardians($group, $season)
 * @method static \Illuminate\Database\Query\Builder|\App\Group active()
 * @method static \Illuminate\Database\Query\Builder|\App\Group inactive()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|TeamSet[] $teamSets
 * @property string $settings
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereSettings($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group withoutActivePlayers($season)
 * @method static \Illuminate\Database\Query\Builder|\App\Group hasPendingRegistrationPayments($pendingSince = null, $playerCount = null)
 * @mixin \Eloquent
 *
 * @property int $group_type_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Invitation[] $invitations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spectator[] $spectators
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TournamentQuizmaster[] $tournamentQuizmasters
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Group hasPendingTournamentRegistrationFees(\App\Tournament $tournament)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereGroupTypeId($value)
 */
class Group extends Model
{
    use CanDeactivate;

    protected $guarded = ['id', 'guid'];

    protected $attributes = [
        'program_id'    => Program::BEGINNER,
        'inactive'      => null,
    ];

    protected $dates = ['inactive', 'updated_at', 'created_at'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each group
        static::creating(function ($group) {
            $group->guid = Uuid::uuid4();

            return true;
        });
    }

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    public function meetingAddress() : BelongsTo
    {
        return $this->belongsTo(Address::class, 'meeting_address_id');
    }

    public function players() : BelongsToMany
    {
        // if this relation is updated, update Season too
        return $this->belongsToMany(Player::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size', 'inactive', 'memory_master')
            ->withTimestamps()
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC');
    }

    /**
     * @return Builder
     */
    public function guardians(Season $season)
    {
        $group = $this;

        return User::whereHas('players', function (Builder $q) use ($season, $group) {
            $q->join('player_season', 'player_season.player_id', '=', 'players.id')
                    ->active($season)
                    ->whereHas('groups', function (Builder $q) use ($season, $group) {
                        $q->where('group_id', $group->id);
                        $q->where('season_id', $season->id);
                    });
        });
    }

    public function tournamentQuizmasters() : HasMany
    {
        return $this->hasMany(TournamentQuizmaster::class);
    }

    public function spectators() : HasMany
    {
        return $this->hasMany(Spectator::class);
    }

    public function invitations() : HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function wordpressLocation()
    {
        return Location::where('location_extrafields', 'like', '%'.$this->guid.'%')->first();
    }

    /**
     * Query groups by beginner or teen.
     */
    public function scopeByProgram(Builder $query, $program)
    {
        if (is_string($program)) {
            $program = Program::where('slug', $program)->first()->id;
        }

        return $query->where('groups.program_id', $program);
    }

    /**
     * Query scope for active guardians.
     */
    public function scopeActiveGuardians(Builder $query, Group $group, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
            $q->where('seasons.id', $season->id);
        })
            ->whereNull('player_season.inactive');
    }

    /**
     * Groups with no players for a given season.
     */
    public function scopeWithoutActivePlayers(Builder $query, Season $season)
    {
        return $query->whereDoesntHave('players', function (Builder $q) use ($season) {
            $q->active($season);
        });
    }

    /**
     * Query scope for inactive guardians.
     */
    public function scopeInactiveGuardians(Builder $query, Group $group, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
            $q->where('seasons.id', $season->id);
        })
            ->whereNotNull('player_season.inactive');
    }

    public function scopeHasPendingRegistrationPayments(Builder $query, Season $season, Carbon $pendingSince = null)
    {
        return $query->whereHas('players', function (Builder $q) use ($season, $pendingSince) {
            if ($pendingSince != null) {
                $q->where('player_season.created_at', '>=', $pendingSince->toDateTimeString());
            }

            $q->pendingRegistrationPayment($season);
        });
    }

    public function seasonalFee(array $playerIds) : float
    {
        // requires 1 season, since they've registered at this point, but just haven't paid
        $firstYearPlayercount = Player::whereIn('id', $playerIds)->has('seasons', '=', 1)->count();

        if (Setting::firstYearDiscount() == 100 && $firstYearPlayercount == count($playerIds)) {
            return round(0, 2);
        }

        $total = $this->program->registration_fee * count($playerIds);

        if (Setting::firstYearDiscount() > 0) {
            $discount = (Setting::firstYearDiscount() / 100) * ($this->program->registration_fee * $firstYearPlayercount);

            $total -= $discount;
        }

        return round($total, 2);
    }

    public function scopeHasPendingTournamentRegistrationFees(Builder $query, Tournament $tournament)
    {
        $previousEntry = false;
        if ($tournament->hasFee(ParticipantType::QUIZMASTER)) {
            $query->whereHas('tournamentQuizmasters', function (Builder $q) {
                $q->whereNull('receipt_id');
            });
            $previousEntry = true;
        }

        if ($tournament->hasFee(ParticipantType::ADULT)) {
            $builderMethod = $previousEntry ? 'orWhereHas' : 'whereHas';
            $query->{$builderMethod}('spectators', function (Builder $q) {
                $q->whereNull('receipt_id');
                $q->whereNull('spouse_first_name');
                $q->whereDoesntHave('minors', function (Builder $q) {
                    $q->where('tournament_spectator_minors.spectator_id');
                });
            });
            $previousEntry = true;
        }

        if ($tournament->hasFee(ParticipantType::FAMILY)) {
            $builderMethod = $previousEntry ? 'orWhereHas' : 'whereHas';
            $query->{$builderMethod}('spectators', function (Builder $q) {
                $q->whereNull('receipt_id');
                $q->where(function ($q) {
                    $q->whereNotNull('spouse_first_name');
                    $q->orWhereHas('minors', function (Builder $q) {
                        $q->where('tournament_spectator_minors.spectator_id');
                    });
                });
            });
            $previousEntry = true;
        }

        if ($tournament->hasFee(ParticipantType::TEAM)) {
            $builderMethod = $previousEntry ? 'orWhereHas' : 'whereHas';
            $query->{$builderMethod}('teamSets', function (Builder $q) use ($tournament) {
                $q->where('team_sets.tournament_id', $tournament->id);
                $q->whereHas('teams', function ($q) {
                    $q->whereNull('teams.receipt_id');
                });
            });
            $previousEntry = true;
        }

        if ($tournament->hasFee(ParticipantType::PLAYER)) {
            $builderMethod = $previousEntry ? 'orWhereHas' : 'whereHas';
            $query->{$builderMethod}('teamSets', function (Builder $q) use ($tournament) {
                $q->where('team_sets.tournament_id', $tournament->id);
                $q->whereHas('teams', function ($q) {
                    $q->whereNull('teams.receipt_id');
                });
                $q->leftJoin('tournament_players', function (JoinClause $join) use ($tournament) {
                    $join->on('tournament_players.tournament_id', '=', DB::raw($tournament->id));
                });
                $q->whereNull('tournament_players.receipt_id');
            });
        }

        return $query;
    }

    /**
     * Get groups near a another address.
     *
     * @param Builder $q
     * @param Address $address
     * @param null    $miles
     *
     * @return $this
     */
    public function scopeNear(Builder $q, Address $address, $miles = null)
    {
        if (is_null($miles)) {
            $miles = Config::get('biblebowl.groups.nearby');
        }

        return $q->active()->with([
            'address' => function ($q) use ($miles, $address) {
                $q->whereNotNull($address->getLatColumn())
                    ->whereNotNull($address->getLngColumn())
                    ->within($miles, 'miles', $address->latitude, $address->longitude);
            },
        ]);
    }

    public static function validationRules($groupAlreadyExists = false)
    {
        // Check to see if a group is a duplicate by looking at the location where they meet (zip code or city/state
        // and their program/name when the group is created
        Validator::extend('isnt_duplicate', function ($attribute, $value, $parameters, $validator) {
            $meetingAddress = Address::findOrFail($validator->getData()['meeting_address_id']);
            $group = Group::where('name', $value)
                ->where('program_id', $validator->getData()['program_id'])
                ->whereHas('meetingAddress', function ($query) use ($meetingAddress) {
                    $query->orWhere(function ($query) use ($meetingAddress) {
                        $query->where('city', '=', $meetingAddress->city);
                        $query->where('state', '=', $meetingAddress->state);
                    })
                    ->where('zip_code', '=', $meetingAddress->zip_code);
                })->first();

            return is_null($group);
        });

        return [
            'name'              => 'required|max:128'.($groupAlreadyExists ? '' : '|isnt_duplicate'),
            'program_id'        => 'required',
            'owner_id'          => 'required|exists:users,id',
            'address_id'        => 'required|exists:addresses,id',
        ];
    }

    public static function validationMessages()
    {
        return [
            'name.isnt_duplicate' => "This group already exists, please contact that group's owner",
        ];
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function program() : BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function seasons() : HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function teamSets() : HasMany
    {
        return $this->hasMany(TeamSet::class);
    }

    public function isOwner(User $user) : bool
    {
        return $user->id == $this->owner_id;
    }

    public function setOwner(User $newOwner) : bool
    {
        if ($this->isOwner($newOwner)) {
            return false;
        }

        DB::beginTransaction();

        $previousOwner = $this->owner;

        // remove the owner as Head Coach if they weren't
        $ownerHasOtherGroups = $this->owner->groups()->where('groups.id', '!=', $this->id)->count() > 0;
        if ($ownerHasOtherGroups === false) {
            $role = Role::where('name', Role::HEAD_COACH)->firstOrFail();
            $newOwner->retract($role);
        }

        // add new owner as head coach
        if ($this->isHeadCoach($newOwner) === false) {
            $this->addHeadCoach($newOwner);
        }

        $this->update([
            'owner_id' => $newOwner->id,
        ]);

        DB::commit();

        Mail::to($newOwner)->queue(new GroupOwnershipTransferred($this, $previousOwner, $newOwner));

        return true;
    }

    public function addHeadCoach(User $user)
    {
        $user->groups()->attach($this->id);

        // make the owner a head coach if they aren't already
        if ($user->isNotA(Role::HEAD_COACH)) {
            $role = Role::where('name', Role::HEAD_COACH)->firstOrFail();
            $user->assign($role);
        }
    }

    public function isHeadCoach(User $user) : bool
    {
        return $this->whereHas('users', function ($q) use ($user) {
            $q->where('group_user.group_id', $this->id)
                ->where('group_user.user_id', $user->id);
        })->count() > 0;
    }

    public function removeHeadCoach(User $user)
    {
        $user->groups()->detach($this->id);

        if ($user->groups()->count() == 0) {
            $role = Role::where('name', Role::HEAD_COACH)->firstOrFail();
            $user->retract($role);
        }
    }

    public function teamSet(Tournament $tournament) : ?TeamSet
    {
        return $this->teamSets()->where([
            'group_id'      => $this->id,
            'tournament_id' => $tournament->id,
        ])->first();
    }

    public function isActive() : bool
    {
        return is_null($this->inactive);
    }

    public function isInactive() : bool
    {
        return $this->isActive() === false;
    }

    public function setNameAttribute($attribute)
    {
        $this->attributes['name'] = ucwords(strtolower(trim($attribute)));
    }

    /**
     * Registration link to register for this specific group.
     */
    public function registrationReferralLink() : string
    {
        return 'group/'.$this->guid.'/register';
    }

    /**
     * Registration link to register for this specific group.
     */
    public function registerLink() : string
    {
        return '/register/group/'.$this->id;
    }

    /**
     * @param $value
     *
     * @return Settings
     */
    public function getSettingsAttribute($value)
    {
        if (is_null($value)) {
            return app(Settings::class);
        }

        return new Settings(json_decode($value, true));
    }

    /**
     * @param Settings $value
     */
    public function setSettingsAttribute($value)
    {
        if ($value instanceof Settings) {
            $this->attributes['settings'] = $value->toJson();
        } else {
            $this->attributes['settings'] = json_encode($value);
        }
    }
}
