<?php namespace BibleBowl\Http\Controllers\Auth;

use BibleBowl\Auth\ThirdPartyRegistrar;
use BibleBowl\Auth\ThirdPartyAuthenticator;
use Illuminate\Http\Request;

class ThirdPartyAuthController extends AuthController
{

	/**
	 * Get authorization from the given provider
	 *
	 * @param $provider
	 *
	 * @return mixed
	 * @throws \BibleBowl\Auth\UnsupportedProvider
	 */
	public function login(Request $request, $provider, ThirdPartyAuthenticator $authenticator, ThirdPartyRegistrar $registrar) {
		// If there's no code, get authorization from the provider
		if (!$request->has('code')) {
			return $authenticator->getAuthorization($provider);
		}

		$user = $authenticator->findOrCreateUser($provider);

		$this->auth->login($user);

		return redirect()->intended($this->redirectPath());
	}

}
