<?php namespace BibleBowl;

use Rhumsaa\Uuid\Uuid;
use BibleBowl\Support\CanDeactivate;
use Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
 */
class Group extends Model {
    use CanDeactivate;

    protected $guarded = ['id', 'guid'];

    protected $attributes = [
        'program_id'    => Program::BEGINNER,
        'inactive'      => null
    ];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($group) {
            $group->guid = Uuid::uuid4();
            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address() {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meetingAddress() {
        return $this->belongsTo(Address::class, 'meeting_address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players() {
        // if this relation is updated, update Season too
        return $this->belongsToMany(Player::class, 'player_season')
            ->withPivot('group_id', 'grade', 'shirt_size')
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
     * Query
     *
     * @param Builder $q
     * @param Address $address
     * @param null    $miles
     *
     * @return $this
     */
    public function scopeNear(Builder $q, Address $address, $miles = null) {
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

    public static function validationRules()
    {
        // Check to see if a group is a duplicate by looking at the location where they meet (zip code or city/state
        // and their program/name
        Validator::extend('isnt_duplicate', function($attribute, $value, $parameters, $validator) {
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
            'name'			=> 'required|max:128|isnt_duplicate',
            'program_id'    => 'required',
            'owner_id'		=> 'required|exists:users,id',
            'address_id'	=> 'required|exists:addresses,id'
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
    public function users() {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program() {
        return $this->belongsTo(Program::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function seasons() {
        return $this->hasMany(Season::class);
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

    public function setNameAttribute ($attribute)
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
     * Join link to register for this specific group.
     * Used when the player has already registered with NBB
     *
     * @return string
     */
    public function joinLink()
    {
        return '/join/group/'.$this->id;
    }
}
