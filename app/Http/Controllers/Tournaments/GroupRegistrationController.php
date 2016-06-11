<?php namespace BibleBowl\Http\Controllers\Tournaments;

use BibleBowl\Competition\Tournaments\GroupRegistration;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\TeamSet;
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

    public function setTeamSet($slug, TeamSet $teamSet)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        /** @var GroupRegistration $groupRegistration */
        $groupRegistration = Session::tournamentGroupRegistration();
        $groupRegistration->setTournament($tournament);
        $groupRegistration->setTeamSet($teamSet);
        Session::setTournamentGroupRegistration($groupRegistration);

        return redirect('tournaments/'.$tournament->slug.'/group/quizmasters');
    }

    public function quizmasters($slug)
    {
        /** @var GroupRegistration $registration */
        $registration = Session::tournamentGroupRegistration();

        return view('tournaments.quizmasters', [
            'tournament'    => Tournament::where('slug', $slug)->firstOrFail(),
            'teamSet'       => $registration->teamSet()
        ]);
    }
}
