<?php namespace BibleBowl;

use App;
use BibleBowl\Support\Scrubber;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Rhumsaa\Uuid\Uuid;
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
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	const STATUS_UNCONFIRMED = 0;
	const STATUS_CONFIRMED = 1;

	use Authenticatable, CanResetPassword, EntrustUserTrait;

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

	protected $dates = ['birthday'];

	public static function boot()
	{
		parent::boot();

		//assign a guid for each user
		static::creating(function ($user) {
			$user->guid = uniqid();
			return true;
		});
	}

	public static function validationRules(User $userBeingUpdated = null)
	{
		$rules = [
			'first_name'	=> 'required|max:32',
			'last_name'		=> 'required|max:32',
			'email'			=> 'required|email|max:255|unique:users',
			'password'		=> 'confirmed|min:6|max:60',
			'phone'			=> 'required|integer|digits:10',
			'gender'		=> 'required'
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
	public function providers() {
		return $this->hasMany(UserProvider::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function addresses() {
		return $this->hasMany(Address::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function groups() {
		return $this->hasMany(Group::class, 'owner_id')->orderBy('name', 'ASC');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function players() {
		return $this->hasMany(Player::class, 'guardian_id')->orderBy('birthday', 'DESC');
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
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return $this->first_name.' '.$this->last_name;
	}

	public function setEmailAttribute ($attribute)
	{
		/** @var Scrubber $scrubber */
		$scrubber = App::make(Scrubber::class);
		$this->attributes['email'] = $scrubber->email($attribute);
	}

	public function setFirstNameAttribute ($attribute)
	{
		$this->attributes['first_name'] = ucwords(strtolower(trim($attribute)));
	}

	public function setLastNameAttribute ($attribute)
	{
		$this->attributes['last_name'] = ucwords(strtolower(trim($attribute)));
	}

	public function setPhoneAttribute ($attribute)
	{
		/** @var Scrubber $scrubber */
		$scrubber = App::make(Scrubber::class);
		$this->attributes['phone'] = $scrubber->phone($attribute);
	}
}
