<?php namespace BibleBowl\Http\Controllers\Tournaments;

use BibleBowl\Competition\Tournaments\GroupRegistration;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\TeamSet;
use BibleBowl\Tournament;
use Session;

class GroupRegistrationController extends Controller
{

    public function index($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        $group = Session::group();
        return view('tournaments.registration.group-overview', [
            'tournament'    => $tournament,
            'group'         => $group,

            // show unpaid first, then paid
            'quizmasters'   => $tournament->tournamentQuizmasters()->with('user')->where('group_id', $group->id)->orderBy('receipt_id', 'ASC')->get()
        ]);
    }

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

        /** @var GroupRegistration $registration */
        $registration = Session::tournamentGroupRegistration();
        $registration->setTournament($tournament);
        $registration->setTeamSet($teamSet);
        Session::setTournamentGroupRegistration($registration);

        return redirect('tournaments/group/quizmasters');
    }

    public function quizmasters()
    {
        /** @var GroupRegistration $registration */
        $registration = Session::tournamentGroupRegistration();

        return view('tournaments.quizmasters', [
            'tournament'    => $registration->tournament(),
            'teamSet'       => $registration->teamSet()
        ]);
    }
}
