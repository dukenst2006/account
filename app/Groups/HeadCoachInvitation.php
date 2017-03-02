<?php

namespace App\Groups;

use App\Invitation;
use App\User;
use App\Users\AcceptsInvitations;
use Auth;
use Session;

class HeadCoachInvitation
{
    use AcceptsInvitations {
        accept as parentAccept;
    }

    public function accept(User $accepter, Invitation $invitation) : bool
    {
        $invitation->group->addHeadCoach($accepter);

        // default user to this group
        if (Auth::user() != null && Session::doesntHaveGroup()) {
            Session::setGroup($invitation->group);
        }

        return $this->parentAccept($accepter, $invitation);
    }
}
