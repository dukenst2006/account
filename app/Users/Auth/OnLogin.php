<?php namespace BibleBowl\Users\Auth;

use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\User;
use Session;

class OnLogin
{
    /** Even name is defined by Laravel */
    const EVENT = 'auth.login';

    public function handle(User $user)
    {
        $user->updateLastLogin();

        // current session is the most recent
        Session::setSeason(Season::current()->first());

        // if user is a coach set current "Group" upon login
        if ($user->hasRole(Role::HEAD_COACH) && $user->groups->count() > 0) {
            Session::setGroup($user->groups->first());
        }
    }
}