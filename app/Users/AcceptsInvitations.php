<?php

namespace BibleBowl\Users;

use BibleBowl\Invitation;
use BibleBowl\User;

trait AcceptsInvitations
{
    abstract protected function invitation() : Invitation;

    public function accept(User $accepter) : bool
    {
        $this->invitation()->update([
            'status' => Invitation::ACCEPTED,
        ]);

        return true;
    }
}
