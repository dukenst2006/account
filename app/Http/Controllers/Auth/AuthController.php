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

	public function __construct(Registrar $registrar)
	{
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Send the response after the user was authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  bool  $throttles
	 * @return \Illuminate\Http\Response
	 */
	protected function handleUserWasAuthenticated(Request $request, $throttles)
	{
		if ($throttles) {
			$this->clearLoginAttempts($request);
		}

		//require email is confirmed before continuing
		$user = Auth::user();
		if ($user->status == User::STATUS_UNCONFIRMED) {
			Auth::logout();
			Event::fire('auth.resend.confirmation', [$user]);

			return redirect()->back()
				->withInput($request->only($this->loginUsername(), 'remember'))
				->withErrors([
					'email' => "Your email address is not yet confirmed.  We've resent your confirmation email.",
				]);
		}

		if (method_exists($this, 'authenticated')) {
			return $this->authenticated($request, Auth::guard($this->getGuard())->user());
		}

		return redirect()->intended($this->redirectPath());
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
