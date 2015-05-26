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

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	const STATUS_UNCONFIRMED = 0;
	const STATUS_CONFIRMED = 1;

	use Authenticatable, CanResetPassword;

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

	public static function boot()
	{
		parent::boot();

		//assign a guid for each user
		static::creating(function ($user) {
			$user->guid = Uuid::uuid5(Uuid::NAMESPACE_DNS, Config::get('biblebowl.uuid.name'));
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
	public function children() {
		return $this->hasMany('BibleBowl\Children');
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

}
