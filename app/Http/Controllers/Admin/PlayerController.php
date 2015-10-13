<?php namespace BibleBowl\Http\Controllers\Admin;

use Input;
use BibleBowl\Player;

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
            'players' => $players->appends(Input::only('q'))
        ]);
    }

    public function show($playerId)
    {
        return view('/admin/players/show', [
            'player' => Player::findOrFail($playerId)
        ]);
    }

}
