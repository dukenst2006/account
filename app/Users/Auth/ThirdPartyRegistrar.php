<?php namespace BibleBowl\Users\Auth;

use App;
use BibleBowl\User;
use DB;
use Laravel\Socialite\AbstractUser;
use Validator;

class ThirdPartyRegistrar
{
    /** @var Registrar $registrar */
    protected $registrar;

    public function __construct(Registrar $registrar)
    {
        $this->registrar = $registrar;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'email'            => 'required|email|max:255|unique:users',
        ]);
    }

    /**
     * @param              $provider
     * @param AbstractUser $providerUser
     *
     * @return User
     * @internal param \Laravel\Socialite\Two\User $user
     */
    public function create($provider, AbstractUser $providerUser)
    {
        $name = explode(' ', $providerUser->getName());
        $userData = [
            'first_name'    => $name[0],
            'last_name'     => isset($name[1]) ? $name[1] : '',
            'email'         => $providerUser->getEmail(),
            'avatar'        => $providerUser->getAvatar(),
            'status'        => User::STATUS_CONFIRMED
        ];

        DB::beginTransaction();
        $user = $this->registrar->create($userData);
        $userProvider = App::make('BibleBowl\UserProvider', [[
            'provider'        => $provider,
            'provider_id'    => $providerUser->getId()
        ]]);
        $user->providers()->save($userProvider);
        DB::commit();

        return $user;
    }
}
