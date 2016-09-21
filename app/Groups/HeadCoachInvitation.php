<?php

namespace BibleBowl\Groups;

use Auth;
use BibleBowl\Invitation;
use BibleBowl\User;
use BibleBowl\Users\AcceptsInvitations;
use Session;

class HeadCoachInvitation
{
    use AcceptsInvitations {
        accept as parentAccept;
    }

    /** @var Invitation */
    protected $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function accept(User $accepter) : bool
    {
        $this->invitation->group->addHeadCoach($accepter);

        // default user to this group
        if (Auth::user() != null && Session::doesntHaveGroup()) {
            Session::setGroup($this->invitation->group);
        }

        return $this->parentAccept($accepter);
    }

    protected function invitation() : Invitation
    {
        return $this->invitation;
    }
}
