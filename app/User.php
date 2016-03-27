<?php namespace BibleBowl;

use App;
use BibleBowl\Support\Scrubber;
use BibleBowl\Users\Settings;
use Carbon\Carbon;
use DatabaseSeeder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;
use Silber\Bouncer\Database\HasRoles;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * BibleBowl\User
 *
 * @property integer $id
 * @property boolean $status
 * @property string $guid
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $gender
 * @property string $avatar
 * @property Settings $settings
 * @property string $last_login
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|UserProvider[] $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|Address[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.role')[] $roles
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereLastLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereUpdatedAt($value)
 * @method static \BibleBowl\User byProviderId($id)
 * @property integer $primary_address_id
 * @property-read Address $primaryAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $ownedGroups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User wherePrimaryAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereSettings($value)
 * @property-read \BibleBowl\Cart $cart
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Item[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Receipt[] $orders
 * @property-read mixed $shop_id
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    const STATUS_UNCONFIRMED = 0;
    const STATUS_CONFIRMED = 1;

    use Authenticatable,
        CanResetPassword,
        HasRolesAndAbilities {
            HasRolesAndAbilities::assign as parentAssign;
            HasRolesAndAbilities::retract as parentRetract;
        }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The default attributes for this model.
     *
     * @var []
     */
    protected $attributes = [
        'status' => self::STATUS_UNCONFIRMED
    ];

    protected $casts = [
        'settings' => Settings::class
    ];

    /**
     * The attributes that are guarded against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id', 'guid', 'remember_token', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['birthday', 'last_login'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each user
        static::creating(function ($user) {
            $user->guid = Uuid::uuid4();
            return true;
        });
    }

    public static function validationRules(User $userBeingUpdated = null)
    {
        $rules = [
            'first_name'    => 'required|max:32',
            'last_name'        => 'required|max:32',
            'email'            => 'required|email|max:255|unique:users',
            'password'        => 'confirmed|min:6|max:60',
            'phone'            => 'required|integer|digits:10',
            'gender'        => 'required'
        ];

        if (!is_null($userBeingUpdated)) {
            $rules['email'] .= ',email,'.$userBeingUpdated->id;
        }

        return $rules;
    }

    public function updateLastLogin()
    {
        return $this->update([
            'last_login' => Carbon::now()
        ]);
    }

    /**
     * Find a user by its provider id
     *
     * @param $id
     *
     * @return mixed
     */
    public function scopeByProviderId(Builder $query, $id)
    {
        return $query->whereHas('providers',
            function (Builder $query) use ($id) {
                $query->where('provider_id', $id);
            })
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function providers()
    {
        return $this->hasMany(UserProvider::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function primaryAddress()
    {
        return $this->hasOne(Address::class, 'id', 'primary_address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)->orderBy('name', 'ASC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedGroups()
    {
        return $this->hasMany(Group::class, 'owner_id')->orderBy('name', 'ASC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany(Player::class, 'guardian_id')->orderBy('birthday', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Receipt::class)->orderBy('created_at', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments()
    {
        return $this->hasMany(Tournament::class)->orderBy('start', 'ASC');
    }

    /**
     * Determines if this user still lacks basic account information
     *
     * @return bool
     */
    public function requiresSetup()
    {
        return is_null($this->first_name) || is_null($this->last_name);
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

        return app(Settings::class, [$this->fromJson($value)]);
    }

    /**
     * @param Settings $value
     */
    public function setSettingsAttribute(Settings $value)
    {
        $this->attributes['settings'] = $value->toJson();
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function setEmailAttribute($attribute)
    {
        /** @var Scrubber $scrubber */
        $scrubber = App::make(Scrubber::class);
        $this->attributes['email'] = $scrubber->email($attribute);
    }

    public function setFirstNameAttribute($attribute)
    {
        $this->attributes['first_name'] = ucwords(strtolower(trim($attribute)));
    }

    public function setLastNameAttribute($attribute)
    {
        $this->attributes['last_name'] = ucwords(strtolower(trim($attribute)));
    }

    public function setPhoneAttribute($attribute)
    {
        /** @var Scrubber $scrubber */
        $scrubber = App::make(Scrubber::class);
        $this->attributes['phone'] = $scrubber->phone($attribute);
    }

    /**
     * Assign the given role to the model.
     *
     * @param  \Silber\Bouncer\Database\Role|string  $role
     * @return $this
     */
    public function assign($role)
    {
        if (DatabaseSeeder::isSeeding() === false && $role->hasMailchimpInterest()) {
            Event::fire('user.role.added', [$this, $role]);
        }
        
        return $this->parentAssign($role);
    }

    /**
     * Retract the given role from the model.
     *
     * @param  \Silber\Bouncer\Database\Role|string  $role
     * @return $this
     */
    public function retract($role)
    {
        if (DatabaseSeeder::isSeeding() === false && $role->hasMailchimpInterest()) {
            Event::fire('user.role.removed', [$this, $role]);
        }
        
        return $this->parentRetract($role);
    }
}
