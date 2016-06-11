<?php namespace BibleBowl\Http\Controllers;

use BibleBowl\Competition\Tournaments\GroupRegistration;
use Session;
use BibleBowl\TeamSet;
use BibleBowl\Tournament;

class TournamentsController extends Controller
{

    public function show($slug)
    {
        return view('tournaments.show', [
            'tournament'    => $tournament = Tournament::where('slug', $slug)->firstOrFail(),
            'events'        => $tournament->events()->with('type')->get(),
        ]);
    }
}
