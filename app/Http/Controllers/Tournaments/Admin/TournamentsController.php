<?php

namespace App\Http\Controllers\Tournaments\Admin;

use App\Competition\TournamentCreator;
use App\Competition\TournamentUpdater;
use App\EventType;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupEditRequest;
use App\Http\Requests\TournamentCreateRequest;
use App\Http\Requests\TournamentCreatorOnlyRequest;
use App\Http\Requests\TournamentEditRequest;
use App\ParticipantType;
use App\Program;
use App\Role;
use App\Season;
use App\Tournament;
use App\TournamentType;
use Auth;
use Session;

class TournamentsController extends Controller
{
    public function index()
    {
        return view('tournaments.admin.index', [
            'tournaments' => Tournament::where('season_id', Session::season()->id)
                ->where('creator_id', Auth::user()->id)
                ->orderBy('start', 'DESC')
                ->get(),
        ]);
    }

    public function show($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('tournaments.admin.show', [
            'tournament'        => $tournament,
            'participantFees'   => $tournament->participantFees()->with('participantType')->get()->keyBy('participant_type_id'),
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $programs = [];
        foreach (Program::all() as $program) {
            $programs[$program->id] = $program.'';
        }

        $types = [];
        foreach (TournamentType::all() as $type) {
            if (Auth::user()->isA(Role::ADMIN) === false && $type->id == TournamentType::NATIONAL) {
                continue;
            }
            $types[$type->id] = $type->name.'';
        }

        return view('tournaments.admin.create', [
            'programs'          => $programs,
            'eventTypes'        => EventType::orderBy('name', 'ASC')->get(),
            'participantTypes'  => ParticipantType::orderBy('name', 'ASC')->get(),
            'tournamentTypes'   => $types,
            'defaultEventTypes' => [
                EventType::WRITTEN_TEST,
                EventType::ROUND_ROBIN,
                EventType::DOUBLE_ELIMINATION,
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function store(TournamentCreateRequest $request, TournamentCreator $tournamentCreator)
    {
        $tournament = $tournamentCreator->create(
            Auth::user(),
            Session::season(),
            $request->except('_token', 'eventTypes', 'participantTypes'),
            $request->get('eventTypes', []),
            $request->get('participantTypes', [])
        );

        return redirect('/admin/tournaments');
    }

    /**
     * @param TournamentCreatorOnlyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(TournamentCreatorOnlyRequest $request, $id)
    {
        $tournament = Tournament::with('participantFees')->findOrFail($id);

        return view('tournaments.admin.edit', [
            'tournament'        => $tournament,
            'participantFees'   => $tournament->participantFees->keyBy('participant_type_id'),
            'participantTypes'  => ParticipantType::orderBy('name', 'ASC')->get(),
        ]);
    }

    /**
     * @param GroupEditRequest $request
     * @param                  $id
     *
     * @return mixed
     */
    public function update(TournamentEditRequest $request, $id, TournamentUpdater $tournamentUpdater)
    {
        $tournament = Tournament::findOrFail($id);

        $tournamentUpdater->update(
            $tournament,
            $request->except('participantTypes'),
            $request->get('participantTypes')
        );

        return redirect('/admin/tournaments/'.$id)->withFlashSuccess('Your changes were saved');
    }
}
