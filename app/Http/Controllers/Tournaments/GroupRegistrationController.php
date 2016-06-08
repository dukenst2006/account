<?php namespace BibleBowl\Http\Controllers\Tournaments;

use Auth;
use BibleBowl\Competition\TournamentCreator;
use BibleBowl\Competition\TournamentUpdater;
use BibleBowl\EventType;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Http\Requests\TournamentCreateRequest;
use BibleBowl\Http\Requests\TournamentCreatorOnlyRequest;
use BibleBowl\Http\Requests\TournamentEditRequest;
use BibleBowl\ParticipantType;
use BibleBowl\Program;
use BibleBowl\Tournament;
use Session;

class GroupRegistrationController extends Controller
{

    public function chooseTeams($slug)
    {
        return view('tournaments.choose-teams', [
            'tournament'    => Tournament::where('slug', $slug)->firstOrFail(),
            'teamSets'      => Session::group()->teamSets()->season(Session::season())->get()
        ]);
    }
}
