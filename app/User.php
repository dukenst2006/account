<?php

namespace BibleBowl;

use App;
use BibleBowl\Support\Scrubber;
use BibleBowl\Users\Notifications\PasswordReset;
use BibleBowl\Users\Settings;
use Carbon\Carbon;
use DatabaseSeeder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;
use Silber\Bouncer\Database\HasRolesAndAbilities;

/**
 * BibleBowl\User.
 *
 * @property int $id
 * @property bool $status
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
 *
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
 *
 * @property int $primary_address_id
 * @property-read Address $primaryAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $ownedGroups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User wherePrimaryAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereSettings($value)
 *
 * @property-read \BibleBowl\Cart $cart
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Item[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Receipt[] $orders
 * @property-read mixed $shop_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Receipt[] $invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Ability[] $abilities
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereIs($role)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereIsAll($role)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereCan($ability, $model = null)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\User whereCannot($ability, $model = null)
 * @mixin \Eloquent
 */
class User extends Model implements
    AuthorizableContract,
    AuthenticatableContract,
    CanResetPasswordContract
{
    const STATUS_UNCONFIRMED = 0;
    const STATUS_CONFIRMED = 1;

    use Notifiable,
        Authorizable,
        Authenticatable,
        CanResetPassword,
        HasRolesAndAbilities {
            HasRolesAndAbilities::assign as parentAssign;
            HasRolesAndAbilities::retract as parentRetract;
        }

    /**
     * The default attributes for this model.
     *
     * @var []
     */
    protected $attributes = [
        'status' => self::STATUS_UNCONFIRMED,
    ];

    protected $casts = [
        'settings' => Settings::class,
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
            'last_name'     => 'required|max:32',
            'email'         => 'required|email|max:255|unique:users',
            'password'      => 'confirmed|min:6|max:60',
            'phone'         => 'required|integer|digits:10',
            'gender'        => 'required',
        ];

        if (!is_null($userBeingUpdated)) {
            $rules['email'] .= ',email,'.$userBeingUpdated->id;
        }

        return $rules;
    }

    public function updateLastLogin()
    {
        return $this->update([
            'last_login' => Carbon::now(),
        ]);
    }

    /**
     * Find a user by its provider id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function scopeByProvider(Builder $query, $provider, $id)
    {
        return $query->whereHas(
            'providers',
            function (Builder $query) use ($provider, $id) {
                $query->where('provider', $provider)
                    ->where('provider_id', $id);
            }
        );
    }

    public function tournamentQuizmasters() : HasMany
    {
        return $this->hasMany(TournamentQuizmaster::class);
    }

    public function providers() : HasMany
    {
        return $this->hasMany(UserProvider::class);
    }

    public function primaryAddress() : HasOne
    {
        return $this->hasOne(Address::class, 'id', 'primary_address_id');
    }

    public function addresses() : HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(Group::class)->orderBy('name', 'ASC');
    }

    public function ownedGroups() : HasMany
    {
        return $this->hasMany(Group::class, 'owner_id')->orderBy('name', 'ASC');
    }

    public function cart() : HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function players() : HasMany
    {
        return $this->hasMany(Player::class, 'guardian_id')->orderBy('birthday', 'DESC');
    }

    public function receipts() : HasMany
    {
        return $this->hasMany(Receipt::class)->orderBy('created_at', 'DESC');
    }

    public function invitationsReceived() : HasMany
    {
        return $this->hasMany(Invitation::class)->orderBy('created_at', 'DESC');
    }

    public function invitationsSent() : HasMany
    {
        return $this->hasMany(Invitation::class, 'inviter_id')->orderBy('created_at', 'DESC');
    }

    public function surveys() : HasMany
    {
        return $this->hasMany(RegistrationSurvey::class);
    }

    public function tournaments() : HasMany
    {
        return $this->hasMany(Tournament::class)->orderBy('start', 'ASC');
    }

    public function spectators() : HasMany
    {
        return $this->hasMany(Spectator::class);
    }

    /**
     * Determines if this user still lacks basic account information.
     */
    public function stillRequiresSetup() : bool
    {
        return is_null($this->primary_address_id);
    }

    public function scopeRequiresSetup(Builder $q)
    {
        $q->whereNull('primary_address_id');
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
     * @param \Silber\Bouncer\Database\Role $role
     *
     * @return $this
     */
    public function assign(Role $role)
    {
        if (DatabaseSeeder::isSeeding() === false && $role->hasMailchimpInterest()) {
            event('user.role.added', [$this, $role]);
        }

        return $this->parentAssign($role);
    }

    /**
     * Retract the given role from the model.
     *
     * @param \Silber\Bouncer\Database\Role|string $role
     *
     * @return $this
     */
    public function retract(Role $role)
    {
        if (DatabaseSeeder::isSeeding() === false && $role->hasMailchimpInterest()) {
            event('user.role.removed', [$this, $role]);
        }

        return $this->parentRetract($role);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($this, $token));
    }
}
