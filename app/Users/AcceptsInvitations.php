<?php

namespace App\Users;

use App\Invitation;
use App\User;

trait AcceptsInvitations
{
    public function accept(User $accepter, Invitation $invitation) : bool
    {
        $invitation->update([
            'status' => Invitation::ACCEPTED,
        ]);

        return true;
    }
}
