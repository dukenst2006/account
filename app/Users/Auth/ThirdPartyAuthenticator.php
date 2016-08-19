<?php namespace BibleBowl\Users\Auth;

use BibleBowl\User;
use Laravel\Socialite\Contracts\Factory as Socialite;

class ThirdPartyAuthenticator
{
    const PROVIDER_FACEBOOK    = 'facebook';
    const PROVIDER_TWITTER        = 'twitter';
    const PROVIDER_GOOGLE        = 'google';

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
     * @param $provider
     *
     * @return User
     * @throws EmailAlreadyInUse
     */
    public function findOrCreateUser($provider)
    {
        /** @var \Laravel\Socialite\Two\User $providerUser */
        $providerUser = $this->socialite->with($provider)->user();
        // @todo update this to use the provider name (facebook, google, etc.)
        $user = User::byProviderId($providerUser->id)->first();
        if (is_null($user)) {
            # Don't allow this email to be registered if it's already in use
            if (User::where('email', $providerUser->getEmail())->count() > 0) {
                throw new EmailAlreadyInUse($providerUser->getEmail().' is already in use by an another account.');
            }

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
