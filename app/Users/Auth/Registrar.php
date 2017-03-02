<?php

namespace App\Users\Auth;

use App;
use App\User;
use Gravatar;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Log;
use Validator;

class Registrar
{
    use DispatchesJobs;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $rules = array_only(User::validationRules(), ['email', 'password']);
        $rules['password'] = 'required|'.$rules['password'];

        if (!App::environment('local', 'testing')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance.
     *
     * @param array $data
     *
     * @return User
     */
    public function create(array $data)
    {
        unset($data['g-recaptcha-response']);
        unset($data['password_confirmation']);

        //use Gravatar if a user has one
        try {
            if (!isset($data['avatar']) && Gravatar::exists($data['email'])) {
                $data['avatar'] = Gravatar::get($data['email']);
            }
        } catch (\ErrorException $e) {
            if (App::environment('local', 'testing') && str_contains($e->getMessage(), 'get_headers(): php_network_getaddresses: getaddrinfo failed: nodename nor servname provided')) {
                Log::debug('We are probably offline, so this is being suppressed to avoid test failures');
            } else {
                throw $e;
            }
        }

        $user = new User($data);

        //third party account creation won't have a password
        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        event('auth.registered', [$user]);

        return $user;
    }
}
