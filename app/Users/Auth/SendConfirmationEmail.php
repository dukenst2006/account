<?php

namespace App\Users\Auth;

use App\User;
use Mail;

class SendConfirmationEmail
{
    /**
     * Handle the event.
     *
     * @param User $user
     *
     * @return void
     */
    public function handle(User $user)
    {
        if ($user->status == User::STATUS_UNCONFIRMED) {
            Mail::to($user)->queue(new ConfirmationEmail($user));
        }
    }
}
