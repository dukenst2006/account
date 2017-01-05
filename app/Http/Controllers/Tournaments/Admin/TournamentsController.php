<?php

namespace BibleBowl\Http\Controllers\Tournaments\Admin;

use Auth;
use BibleBowl\Competition\TournamentCreator;
use BibleBowl\Competition\Tournaments\ShirtSizeExporter;
use BibleBowl\Competition\TournamentUpdater;
use BibleBowl\EventType;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Http\Requests\TournamentCreateRequest;
use BibleBowl\Http\Requests\TournamentCreatorOnlyRequest;
use BibleBowl\Http\Requests\TournamentEditRequest;
use BibleBowl\ParticipantType;
use BibleBowl\Player;
use BibleBowl\Program;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\Spectator;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use BibleBowl\TournamentType;
use Carbon\Carbon;
use DB;
use Html;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
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
