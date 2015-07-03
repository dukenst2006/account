<?php namespace BibleBowl;

use Config;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Rhumsaa\Uuid\Uuid;
use Zizaco\Entrust\Traits\EntrustUserTrait;

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
		return $this->hasMany('BibleBowl\UserProvider');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function addresses() {
		return $this->hasMany('BibleBowl\Address');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function groups() {
		return $this->hasMany('BibleBowl\Group', 'owner_id')->orderBy('name', 'ASC');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function players() {
		return $this->hasMany('BibleBowl\Player', 'guardian_id')->orderBy('birthday', 'DESC');
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

}
