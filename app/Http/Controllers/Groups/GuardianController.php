<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\User;
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
