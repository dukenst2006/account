<?php

namespace App\Http\Controllers\Tournaments\Admin\Registrations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Spectator;
use App\Tournament;
use App\TournamentQuizmaster;

class SpectatorsController extends Controller
{
    public function index(Request $request, Tournament $tournament)
    {
        return view('tournaments.admin.registrations.spectators.index', [
            'tournament'  => $tournament,
            'spectators' => $tournament
                ->spectators()
                ->select('tournament_spectators.*')
                ->leftJoin('users', 'users.id', '=', 'tournament_spectators.user_id')
                ->with('user', 'minors', 'address')
                ->where('tournament_spectators.first_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('tournament_spectators.last_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('tournament_spectators.email', 'LIKE', '%'.$request->get('q').'%')
                ->where('users.first_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('users.last_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('users.email', 'LIKE', '%'.$request->get('q').'%')
                ->paginate(25)
                ->appends($request->only('q')),
        ]);
    }

    public function show(Tournament $tournament, Spectator $spectator)
    {
        return view('tournaments.admin.registrations.spectators.show', [
            'tournament' => $tournament,
            'spectator' => $spectator,
        ]);
    }
}
