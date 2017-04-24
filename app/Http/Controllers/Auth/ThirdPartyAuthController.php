<?php

namespace App\Http\Controllers\Auth;

use App\Users\Auth\EmailAlreadyInUse;
use App\Users\Auth\ThirdPartyAuthenticator;
use App\Users\Auth\ThirdPartyEmailEmpty;
use App\Users\Auth\ThirdPartyRegistrar;
use Auth;
use Illuminate\Http\Request;

class ThirdPartyAuthController extends LoginController
{
    /**
     * Get authorization from the given provider.
     *
     * @param $provider
     *
     * @throws \App\Users\Auth\UnsupportedProvider
     *
     * @return mixed
     */
    public function processLogin(Request $request, $provider, ThirdPartyAuthenticator $authenticator, ThirdPartyRegistrar $registrar)
    {
        // If there's no code, get authorization from the provider
        // oauth_token = compatibility for Twitter
        if (!$request->has('code') && !$request->has('oauth_token')) {
            return $authenticator->getAuthorization($provider);
        }

        try {
            $user = $authenticator->findOrCreateUser($provider);
        } catch (ThirdPartyEmailEmpty $e) {
            return redirect('/login')
                ->withErrors([
                    'email' => "We couldn't get your email address from that account, please try another.",
                ]);
        } catch (EmailAlreadyInUse $e) {
            return redirect('/login')
                ->withErrors([
                    'email' => $e->getMessage(),
                ]);
        }

        Auth::login($user, true);

        // some users aren't being impacted by the middleware on the site
        if ($user->stillRequiresSetup()) {
            return redirect('/account/setup');
        }

        return redirect()->intended($this->redirectPath());
    }
}
