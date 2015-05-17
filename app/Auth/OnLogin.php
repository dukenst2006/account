<?php namespace BibleBowl\Auth;

use BibleBowl\User;

class OnLogin
{
    public function handle(User $user)
    {
        $user->updateLastLogin();
    }
}