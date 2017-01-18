<?php

namespace BibleBowl\Http\Controllers\Auth;

use Auth;
use BibleBowl\Users\Auth\EmailAlreadyInUse;
use BibleBowl\Users\Auth\ThirdPartyAuthenticator;
use BibleBowl\Users\Auth\ThirdPartyEmailEmpty;
use BibleBowl\Users\Auth\ThirdPartyRegistrar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThirdPartyAuthController extends LoginController
{
    /**
     * Get authorization from the given provider.
     *
     * @param $provider
     *
     * @throws \BibleBowl\Users\Auth\UnsupportedProvider
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

        Auth::login($user);

        // some users aren't being impacted by the middleware on the site
        if ($user->requiresSetup()) {
            return redirect('/account/setup');
        }

        return redirect()->intended($this->redirectPath());
    }
}
