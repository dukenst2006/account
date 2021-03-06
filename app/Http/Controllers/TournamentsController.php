<?php

namespace App\Http\Controllers;

use App\Tournament;

class TournamentsController extends Controller
{
    public function show($slug)
    {
        return view('tournaments.show',
            [
            'tournament'            => $tournament = Tournament::where('slug', $slug)->firstOrFail(),
            'events'                => $tournament->events()->with('type')->get(),
            'participantFees'       => $tournament->participantFees()->with('participantType')->get()->keyBy('participant_type_id'),
        ]);
    }
}
