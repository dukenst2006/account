<?php namespace BibleBowl\Http\Controllers\Groups;

use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\Groups\PlayerInactiveToggleRequest;
use BibleBowl\Player;
use BibleBowl\User;
use Session;

class GuardianController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function show(User $guardian)
    {
        return view('group.guardian')->
            withGuardian($guardian);
    }
}
