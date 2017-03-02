<?php

namespace App\Users\Auth;

use App\Role;
use App\Season;
use App\User;
use Illuminate\Auth\Events\Login;
use Session;

class OnLogin
{
    /** Even name is defined by Laravel */
    const EVENT = 'auth.login';

    public function handle(Login $login)
    {
        // skip when an admin is logging in as this user
        if (!Session::canSwitchToAdmin()) {
            $login->user->updateLastLogin();
        }

        // current session is the most recent
        Session::setSeason(Season::current()->first());

        // if user is a coach set current "Group" upon login
        if ($login->user->isA(Role::HEAD_COACH) && $login->user->groups->count() > 0) {
            Session::setGroup($login->user->groups->first());
        }
    }
}
