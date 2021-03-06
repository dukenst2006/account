<?php

namespace App\Http\Controllers\Teams;

use App\Competition\Tournaments\Groups\PlayerInvitationRequestNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamSetGroupOnlyRequest;
use App\Http\Requests\TeamSetUpdateRequest;
use App\Team;
use App\TeamSet;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class TeamSetController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('teamset.index')
                ->with('teamSets', Session::group()->teamSets()->season(Session::season())->get());
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $teamSets = [''];
        $teamSets += Session::group()->teamSets()->season(Session::season())->pluck('name', 'id')->toArray();

        return view('teamset.create')
            ->with('teamSetOptions', $teamSets);
    }

    /**
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->merge([
            'group_id'    => Session::group()->id,
            'season_id'   => Session::season()->id,
        ]);

        $this->validate($request, TeamSet::validationRules());

        DB::transaction(function () use ($request) {
            $teamSet = TeamSet::create($request->except('teamSet'));

            // Copy the teams and players from the selected TeamSet
            if (is_numeric($request->input('teamSet')) && $request->input('teamSet') > 0) {
                $copyFrom = TeamSet::findOrFail($request->input('teamSet'));
                foreach ($copyFrom->teams as $copyFromTeam) {
                    $team = Team::create([
                        'team_set_id'     => $teamSet->id,
                        'name'            => $copyFromTeam->name,
                    ]);
                    $players = [];
                    foreach ($copyFromTeam->players as $player) {
                        $players[$player->id] = [
                            'order' => $player->pivot->order,
                        ];
                    }

                    if (count($players) > 0) {
                        $team->players()->attach($players);
                    }
                }
            }
        });

        return redirect('/teamsets')->withFlashSuccess('Teams have been added');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show(TeamSetGroupOnlyRequest $request, $id)
    {
        return view('teamset.show')
                ->with('teamSet', $request->teamSet())
                ->withPlayers(Session::group()->players()
                    ->active($request->teamSet()->season)
                    ->withoutPendingPayment()
                    ->notOnTeamSet($request->teamSet())
                    ->get());
    }

    /**
     * @param   $request
     * @param   $id
     *
     * @return mixed
     */
    public function update(TeamSetUpdateRequest $request, $id)
    {
        $teamSet = TeamSet::findOrFail($id);
        $teamSet->update($request->only('name'));

        return response()->json();
    }

    /**
     * @param TeamSetGroupOnlyRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf(TeamSetGroupOnlyRequest $request, $id)
    {
        /** @var TeamSet $teamSet */
        $teamSet = TeamSet::with('teams.players')->findOrFail($id);

        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('teamset.pdf', [
            'teamSet'         => $teamSet,
            'user'            => Auth::user(),
            'lastUpdated'     => $teamSet->updated_at->timezone(Auth::user()->settings->timeszone()),
        ]);

        return $pdf->stream();
    }

    public function invite(TeamSetGroupOnlyRequest $request, $id)
    {
        /** @var TeamSet $teamSet */
        $teamSet = TeamSet::findOrFail($id);

        /** @var Team $team */
        $team = Team::findOrFail($request->get('team'));

        $teamSet->tournament->creator->notify(new PlayerInvitationRequestNotification(
            $teamSet->tournament,
            $request->get('player'),
            Session::group(),
            $team->name,
            Auth::user()
        ));

        return redirect('/teamsets/'.$teamSet->id)->withFlashSuccess('Request has been submitted');
    }

    /**
     * @param TeamSetGroupOnlyRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamSetUpdateRequest $request)
    {
        $request->teamSet()->delete();

        return redirect('/teamsets')->withFlashSuccess('Teams have been deleted');
    }
}
