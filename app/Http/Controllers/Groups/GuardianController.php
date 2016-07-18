<?php namespace BibleBowl\Http\Controllers\Groups;

use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Player;
use BibleBowl\User;
use Illuminate\Database\Eloquent\Builder;
use Session;

class GuardianController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function show(User $guardian)
    {
        return view('group.guardian')
            ->withGuardian($guardian)
            ->withPlayers(Session::group()
                ->players()
                ->active(Session::season())
                ->whereHas('guardian', function (Builder $q) use ($guardian) {
                    $q->where('id', $guardian->id);
                })->get()
            );
    }
}
