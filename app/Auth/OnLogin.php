<?php namespace BibleBowl\Auth;

use BibleBowl\User;

class OnLogin
{
    /** Even name is defined by Laravel */
    const EVENT = 'auth.login';

    public function handle(User $user)
    {
        $user->updateLastLogin();
    }
}