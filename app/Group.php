<?php namespace BibleBowl;

use Illuminate\Mail\Message;
use Mail;
use DB;
use BibleBowl\Groups\Settings;
use BibleBowl\Location\Maps\Location;
use BibleBowl\Support\CanDeactivate;
use Carbon\Carbon;
use Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;
use Validator;

/**
 * BibleBowl\Group
 *
 * @property integer $id
 * @property string $guid
 * @property boolean $type
 * @property string $name
 * @property integer $owner_id
 * @property integer $address_id
 * @property integer $meeting_address_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Address $address
 * @property-read mixed $full_name
 * @property-read User $users
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereMeetingAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereUpdatedAt($value)
 * @method static \BibleBowl\Group near($address, $miles = null)
 * @property integer $program_id
 * @property \Carbon\Carbon $inactive
 * @property-read Address $meetingAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read User $owner
 * @property-read Program $program
 * @property-read \Illuminate\Database\Eloquent\Collection|Season[] $seasons
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereProgramId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereInactive($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group byProgram($program)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group activeGuardians($group, $season)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group inactiveGuardians($group, $season)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group active()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group inactive()
 * @property-read \Illuminate\Database\Eloquent\Collection|TeamSet[] $teamSets
 * @property string $settings
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group whereSettings($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group withoutActivePlayers($season)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Group hasPendingRegistrationPayments($pendingSince = null, $playerCount = null)
 * @mixin \Eloquent
 */
class Group extends Model
{
    use CanDeactivate;

    protected $guarded = ['id', 'guid'];

    protected $attributes = [
        'program_id'    => Program::BEGINNER,
        'inactive'      => null
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(GroupType::class, 'group_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meetingAddress()
    {
        return $this->belongsTo(Address::class, 'meeting_address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        // if this relation is updated, update Season too
        return $this->belongsToMany(Player::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size', 'inactive')
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentQuizmasters()
    {
        return $this->hasMany(TournamentQuizmaster::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function wordpressLocation()
    {
        return Location::where('location_extrafields', 'like', '%'.$this->guid.'%')->first();
    }

    /**
     * Query groups by beginner or teen
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
     * Groups with no players for a given season
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

    /**
     * Query scope for inactive guardians.
     */
    public function scopeHasPendingRegistrationPayments(Builder $query, Carbon $pendingSince = null, $playerCount = null)
    {
        return $query->whereHas('players', function (Builder $q) use ($pendingSince) {
            if ($pendingSince != null) {
                $q->where('player_season.created_at', '>', $pendingSince->toDateTimeString());
            }
            
            $q->whereNull('player_season.paid');
        });
    }

    /**
     * Get groups near a another address
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
            }
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
            'address_id'        => 'required|exists:addresses,id'
        ];
    }

    public static function validationMessages()
    {
        return [
            'name.isnt_duplicate' => "This group already exists, please contact that group's owner"
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function teamSets()
    {
        return $this->hasMany(TeamSet::class);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(User $user)
    {
        return $user->id == $this->owner_id;
    }

    public function setOwner(User $user) : bool
    {
        if ($this->isOwner($user)) {
            return false;
        }

        DB::beginTransaction();

        $previousOwner = $this->owner;
        $this->update([
            'owner_id' => $user->id
        ]);

        if ($this->isHeadCoach($user) === false) {
            $this->addHeadCoach($user);
        }

        // remove the owner as Head Coach if they weren't
        $ownerHasOtherGroups = $this->owner->groups()->where('groups.id', '!=', $this->id)->count() > 0;
        if ($ownerHasOtherGroups === false) {
            $role = Role::where('name', Role::HEAD_COACH)->firstOrFail();
            $user->retract($role);
        }

        DB::commit();

        Mail::queue(
            'emails.group-ownership-transfer',
            [
                'header'        => $this->name.' Ownership Transfer',
                'group'         => $this,
                'previousOwner' => $previousOwner,
                'newOwner'      => $user
            ],
            function (Message $message) use ($previousOwner) {
                $message->to($previousOwner->email, $previousOwner->full_name)
                    ->subject($this->name.' Ownership Transfer');
            }
        );

        return true;
    }

    public function addHeadCoach(User $user)
    {
        $user->groups()->attach($this->id);

        // make the owner a head coach if they aren't already
        if ($user->isNot(Role::HEAD_COACH)) {
            $role = Role::where('name', Role::HEAD_COACH)->firstOrFail();
            $user->assign($role);
        }
    }

    public function isHeadCoach(User $user) : bool
    {
        return $this->whereHas('users', function($q) use ($user) {
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

    public function isActive()
    {
        return is_null($this->inactive);
    }

    public function isInactive()
    {
        return $this->isActive() === false;
    }

    public function setNameAttribute($attribute)
    {
        $this->attributes['name'] = ucwords(strtolower(trim($attribute)));
    }

    /**
     * Registration link to register for this specific group
     *
     * @return string
     */
    public function registrationReferralLink()
    {
        return 'group/'.$this->guid.'/register';
    }

    /**
     * Registration link to register for this specific group
     *
     * @return string
     */
    public function registerLink()
    {
        return '/register/group/'.$this->id;
    }

    /**
     * @param $value
     * @return Settings
     */
    public function getSettingsAttribute($value)
    {
        if (is_null($value)) {
            return app(Settings::class);
        }

        return app(Settings::class, [json_decode($value, true)]);
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
