<?php

namespace BibleBowl\Users\Auth;

use BibleBowl\User;
use BibleBowl\UserProvider;
use Laravel\Socialite\Contracts\Factory as Socialite;

class ThirdPartyAuthenticator
{
    const PROVIDER_FACEBOOK = 'facebook';
    const PROVIDER_TWITTER = 'twitter';
    const PROVIDER_GOOGLE = 'google';

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
     * @param   $provider
     *
     * @throws UnsupportedProvider
     *
     * @return mixed
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
     * Find the existing user or create a new one for this account.
     *
     * @param $provider
     *
     * @throws EmailAlreadyInUse
     *
     * @return User
     */
    public function findOrCreateUser($provider) : User
    {
        /** @var \Laravel\Socialite\Two\User $providerUser */
        $providerUser = $this->socialite->driver($provider)->user();

        $user = User::byProvider($provider, $providerUser->id)->first();

        if (strlen($providerUser->getEmail()) < 1) {
            throw new ThirdPartyEmailEmpty();
        }

        if (is_null($user)) {
            $user = User::where('email', $providerUser->getEmail())->first();
            // If provider isn't associated with user, do that now
            if (is_null($user)) {
                return $this->registrar->create($provider, $providerUser);
            }

            $userProvider = app(UserProvider::class, [[
                'provider'        => $provider,
                'provider_id'     => $providerUser->getId(),
            ]]);
            $user->providers()->save($userProvider);

            return $user;
        }

        // update the avatar if it has changed
        if (!is_null($providerUser->getAvatar()) && $user->avatar != $providerUser->getAvatar()) {
            $user->update([
                'avatar' => $providerUser->getAvatar(),
            ]);
        }

        return $user;
    }
}
