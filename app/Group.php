<?php namespace BibleBowl;

use Rhumsaa\Uuid\Uuid;
use BibleBowl\Support\CanDeactivate;
use Config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
 * @method static \BibleBowl\Group nearby($address, $miles = null)
 */
class Group extends Model {
    const TYPE_BEGINNER = 1;
    const TYPE_TEEN = 2;

    use CanDeactivate;

    protected $guarded = ['id', 'guid'];

    protected $attributes = [
        'type'      => self::TYPE_BEGINNER,
        'inactive'  => null
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
     * Query scope for active groups.
     */
    public function scopeActiveGuardians($query, Group $group, Season $season)
    {
        return $query->whereHas('seasons', function (Builder $q) use ($season) {
            $q->where('seasons.id', $season->id);
        })
            ->whereNull('player_season.inactive');
    }

    /**
     * Query scope for inactive groups.
     */
    public function scopeInactiveGuardians($query, Group $group, Season $season)
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
    public function scopeNearby(Builder $q, Address $address, $miles = null) {
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
        return [
            'name'			=> 'required|max:128',
            'type'	        => 'required',
            'owner_id'		=> 'required|exists:users,id',
            'address_id'	=> 'required|exists:addresses,id'
        ];
    }

    /**
     * Append the appropriate suffix to the
     * group name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name.' '.$this->type();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return string
     */
    public function type()
    {
        if ($this->status == self::TYPE_BEGINNER) {
            return 'Beginner Bowl';
        }

        return 'Bible Bowl';
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
