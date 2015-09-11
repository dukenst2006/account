<?php namespace BibleBowl\Http\Controllers\Auth;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\User;
use BibleBowl\Users\Auth\Registrar;
use Event;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	protected $redirectTo = '/dashboard';

	protected $loginPath = '/login';

	public function __construct(Registrar $registrar)
	{
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' 	=> 'required|email',
			'password'  => 'required',
		]);

		$credentials = $request->only('email', 'password');

		if (Auth::attempt($credentials, $request->has('remember')))
		{
			//require email is confirmed before continuing
			$user = Auth::user();
			if ($user->status == User::STATUS_UNCONFIRMED) {
				Auth::logout();
				Event::fire('auth.resend.confirmation', [$user]);
				return redirect($this->loginPath())
					->withErrors([
						'email' => "Your email address is not yet confirmed.  We've resent your confirmation email.",
					]);
			}

			return redirect()->intended($this->redirectPath());
		}

		return redirect($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors([
				'email' => $this->getFailedLoginMessage(),
			]);
	}

    /**
     * Excluding recaptcha is the only thing unique about this from the
     * parent trait
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->except('g-recaptcha-response'));

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->except('g-recaptcha-response')));

        return redirect($this->redirectPath());
    }

	public function validator(array $data)
	{
		return $this->registrar->validator($data);
	}

	public function create(array $data)
	{
		return $this->registrar->create($data);
	}

}
