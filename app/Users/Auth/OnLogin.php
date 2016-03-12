<?php namespace BibleBowl\Users\Auth;

use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\User;
use Illuminate\Auth\Events\Login;
use Session;

class OnLogin
{
    /** Even name is defined by Laravel */
    const EVENT = 'auth.login';

    public function handle(Login $login)
    {
        $login->user->updateLastLogin();

        // current session is the most recent
        Session::setSeason(Season::current()->first());

        // if user is a coach set current "Group" upon login
        if ($login->user->hasRole(Role::HEAD_COACH) && $login->user->groups->count() > 0) {
            Session::setGroup($login->user->groups->first());
        }
    }
}