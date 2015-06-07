<?php namespace BibleBowl\Auth;

use BibleBowl\Season;
use Session;
use BibleBowl\User;

class OnLogin
{
    /** Even name is defined by Laravel */
    const EVENT = 'auth.login';

    public function handle(User $user)
    {
        $user->updateLastLogin();

        // current session is the most recent
        Session::setSeason(Season::orderBy('id', 'DESC')->first());
    }
}