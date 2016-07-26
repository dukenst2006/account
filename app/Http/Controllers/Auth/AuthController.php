<?php namespace BibleBowl\Http\Controllers\Auth;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\User;
use BibleBowl\Users\Auth\Registrar;
use Event;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{

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

    use AuthenticatesAndRegistersUsers, ThrottlesLogins {
        AuthenticatesAndRegistersUsers::getLogin as originalLogin;
        AuthenticatesAndRegistersUsers::getLogout as originalLogout;
    }

    protected $redirectTo = '/dashboard';

    public function __construct(Registrar $registrar)
    {
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin(Request $request)
    {
        if ($request->has('returnUrl')) {
            Session::setRedirectToAfterAuth($request->get('returnUrl'));
        }

        return $this->originalLogin();
    }

    /**
     * Instead of logging the user in, kick them back to the main page requiring
     * they follow the confirmation link in the email we sent
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

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        // logging out as a previous admin switches you
        // back to your original account
        if (Session::canSwitchToAdmin()) {
            $adminUser = Session::adminUser();

            Session::switchUser($adminUser);
            Session::forgetAdminStatus();

            return redirect('dashboard')->withFlashSuccess("You're now logged in as ".$adminUser->full_name);
        }

        return $this->originalLogout();
    }
}
