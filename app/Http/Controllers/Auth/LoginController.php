<?php

namespace BibleBowl\Http\Controllers\Auth;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\User;
use Event;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        AuthenticatesUsers::logout as originalLogout;
        AuthenticatesUsers::showLoginForm as originalShowLoginForm;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        if ($request->has('returnUrl')) {
            Session::setRedirectToAfterAuth($request->get('returnUrl'));
        }

        return $this->originalShowLoginForm();
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, User $user)
    {
        // don't allow the user to login if their account is unconfirmed
        if ($user->status == User::STATUS_UNCONFIRMED) {
            Auth::logout();
            Event::fire('auth.resend.confirmation', [$user]);

            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    'email' => "Your email address is not yet confirmed.  We've resent your confirmation email.",
                ]);
        }

        // some users aren't being impacted by the middleware on the site
        if ($user->requiresSetup()) {
            return redirect('/account/setup');
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // logging out as a previous admin switches you
        // back to your original account
        if (Session::canSwitchToAdmin()) {
            $adminUser = Session::adminUser();

            Session::switchUser($adminUser);
            Session::forgetAdminStatus();

            return redirect('dashboard')->withFlashSuccess("You're now logged in as ".$adminUser->full_name);
        }

        return $this->originalLogout($request);
    }
}
