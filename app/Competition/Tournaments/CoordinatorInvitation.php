<?php

namespace App\Competition\Tournaments;

use App\Invitation;
use App\User;
use App\Users\AcceptsInvitations;
use Auth;
use Session;

class CoordinatorInvitation
{
    use AcceptsInvitations {
        accept as parentAccept;
    }

    public function accept(User $accepter, Invitation $invitation) : bool
    {
        $invitation->tournament->addCoordinator($accepter);

        return $this->parentAccept($accepter, $invitation);
    }
}
