<?php namespace BibleBowl\Auth;

use BibleBowl\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laravel\Socialite\Contracts\Factory as Socialite;

class ThirdPartyAuthenticator
{
	const PROVIDER_FACEBOOK 	= 'facebook';
	const PROVIDER_TWITTER 		= 'twitter';
	const PROVIDER_GOOGLE 		= 'google';

	/** @var Socialite */
	private $socialite;

	/** @var Registrar */
	private $registrar;

	public function __construct(Socialite $socialite, ThirdPartyRegistrar $registrar)
	{
		$this->socialite = $socialite;
		$this->registrar = $registrar;
	}

	/**
	 * @param         $provider
	 *
	 * @return mixed
	 * @throws UnsupportedProvider
	 */
	public function getAuthorization($provider)
	{
		if ($provider == self::PROVIDER_GOOGLE) {
			/*
			 * Only allows google to grab email address
			 * Default scopes array also has: 'https://www.googleapis.com/auth/plus.login'
			 * https://medium.com/@njovin/fixing-laravel-socialite-s-google-permissions-2b0ef8c18205
			 */
			return $this->socialite
				->driver(self::PROVIDER_GOOGLE)
				->scopes([
					'https://www.googleapis.com/auth/plus.me',
					'https://www.googleapis.com/auth/plus.profile.emails.read',
				])
				->redirect();
		} elseif ($provider == self::PROVIDER_FACEBOOK || $provider == self::PROVIDER_TWITTER) {
			return $this->socialite
				->driver($provider)
				->redirect();
		}

		throw new UnsupportedProvider($provider);
	}

	/**
	 * Find the existing user or create a new one for this account
	 *
	 * @param                             $provider
	 *
	 * @return User
	 */
	public function findOrCreateUser($provider)
	{
		$providerUser = $this->socialite->with($provider)->user();
		$user = User::byProviderId($providerUser->id)->first();
		if (is_null($user)) {
			return $this->registrar->create($provider, $providerUser);
		}

		// update the avatar if it has changed
		if (!is_null($providerUser->getAvatar()) && $user->avatar != $providerUser->getAvatar()) {
			$user->update([
				'avatar' => $providerUser->getAvatar()
			]);
		}

		return $user;
	}

}
