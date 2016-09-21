<?php

namespace BibleBowl\Http\Controllers\Auth;

use App\User;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Users\Auth\Registrar;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /** @var Registrar */
    protected $registrar;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Registrar $registrar)
    {
        $this->middleware('guest');

        $this->registrar = $registrar;
    }

    /**
     * Instead of logging the user in, kick them back to the main page requiring
     * they follow the confirmation link in the email we sent.
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->create($request->all());

        return redirect('/login')->withFlashSuccess('Please follow the link in the confirmation email we just sent you.');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return $this->registrar->validator($data);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return $this->registrar->create($data);
    }
}
