<?php namespace BibleBowl\Users\Auth;

use App;
use BibleBowl\User;
use Event;
use Gravatar;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Validator;

class Registrar implements RegistrarContract {

	use DispatchesJobs;

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		$rules = array_only(User::validationRules(), ['email', 'password']);
		$rules['password'] = 'required|'.$rules['password'];

		if (!App::environment('local')) {
			$rules['g-recaptcha-response'] = 'required|captcha';
		}

		return Validator::make($data, $rules);
	}

	/**
	 * Create a new user instance.
	 *
	 * @param  array  $data
	 *
	 * @return User
	 */
	public function create(array $data)
	{
        unset($data['g-recaptcha-response']);
        unset($data['password_confirmation']);

		//use Gravatar if a user has one
		if (!isset($data['avatar']) && Gravatar::exists($data['email'])) {
			$data['avatar'] = Gravatar::get($data['email']);
		}

		$user = App::make(User::class, [$data]);

		//third party account creation won't have a password
		if (isset($data['password'])) {
			$user->password = bcrypt($data['password']);
		}

		$user->save();

		Event::fire('auth.registered', [$user]);

		return $user;
	}

}
