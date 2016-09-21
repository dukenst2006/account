<?php

namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Http\Requests\AdminOnlyRequest;
use BibleBowl\Player;
use Input;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::where('first_name', 'LIKE', '%'.Input::get('q').'%')
            ->orWhere('last_name', 'LIKE', '%'.Input::get('q').'%')
            ->orWhereHas('guardian', function ($q) {
                $q->where('users.first_name', 'LIKE', '%'.Input::get('q').'%')
                    ->orWhere('users.last_name', 'LIKE', '%'.Input::get('q').'%')
                    ->orWhere('email', 'LIKE', '%'.Input::get('q').'%');
            })
            ->with('guardian')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->paginate(25);

        return view('/admin/players/index', [
            'players' => $players->appends(Input::only('q')),
        ]);
    }

    public function show($playerId)
    {
        $player = Player::findOrFail($playerId);

        return view('/admin/players/show', [
            'player'    => $player,
            'seasons'   => $player->seasons()->orderBy('id', 'desc')->get(),
        ]);
    }

    public function destroy(AdminOnlyRequest $request, $playerId)
    {
        $player = Player::findOrFail($playerId);
        $player->delete();

        return redirect('/admin/users/'.$player->guardian_id)->withFlashSuccess('Player has been deleted');
    }
}
