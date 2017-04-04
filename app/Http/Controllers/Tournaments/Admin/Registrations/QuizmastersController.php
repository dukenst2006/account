<?php

namespace App\Http\Controllers\Tournaments\Admin\Registrations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Tournament;
use App\TournamentQuizmaster;

class QuizmastersController extends Controller
{
    public function index(Request $request, Tournament $tournament)
    {
        return view('tournaments.admin.registrations.quizmasters.index', [
            'tournament'  => $tournament,
            'quizmasters' => $tournament
                ->tournamentQuizmasters()
                ->select('tournament_quizmasters.*')
                ->leftJoin('users', 'users.id', '=', 'tournament_quizmasters.user_id')
                ->with('user')
                ->where('tournament_quizmasters.first_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('tournament_quizmasters.last_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('tournament_quizmasters.email', 'LIKE', '%'.$request->get('q').'%')
                ->where('users.first_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('users.last_name', 'LIKE', '%'.$request->get('q').'%')
                ->orWhere('users.email', 'LIKE', '%'.$request->get('q').'%')
                ->paginate(25)
                ->appends($request->only('q')),
        ]);
    }

    public function show(Tournament $tournament, TournamentQuizmaster $quizmaster)
    {
        return view('tournaments.admin.registrations.quizmasters.show', [
            'tournament' => $tournament,
            'quizmaster' => $quizmaster,
        ]);
    }
}
