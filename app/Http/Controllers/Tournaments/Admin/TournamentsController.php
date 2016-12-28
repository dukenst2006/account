<?php

namespace BibleBowl\Http\Controllers\Tournaments\Admin;

use Html;
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
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
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

        return view('tournaments.admin.create', [
            'programs'          => $programs,
            'eventTypes'        => EventType::orderBy('name', 'ASC')->get(),
            'participantTypes'  => ParticipantType::orderBy('name', 'ASC')->get(),
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

    public function exportTeams(int $tournamentId, string $format, Excel $excel)
    {
        $season = Season::current()->firstOrFail();
        $tournament = Tournament::findOrFail($tournamentId);

        // get some info about them this season
        $players = $tournament->eligiblePlayers()
            ->with([
                'seasons' => function ($q) use ($season) {
                    $q->where('seasons.id', $season->id);
                },
                'groups' => function ($q) use ($season) {
                    $q->where('player_season.season_id', $season->id);
                },
                'teams' => function ($q) use ($tournament) {
                    $q->whereHas('teamSet', function ($q) use ($tournament) {
                        $q->where('team_sets.tournament_id', $tournament->id);
                    });
                },
            ])
            ->get();

        $document = $excel->create($tournament->slug.'_teams', function (LaravelExcelWriter $excel) use ($players) {
            $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players) {
                $sheet->appendRow([
                    'Group',
                    'Team',
                    'First Name',
                    'Last Name',
                    'Gender',
                    'Grade',
                    'Added To Team',
                ]);

                /** @var Player $player */
                foreach ($players as $player) {
                    $team = $player->teams->first();
                    $sheet->appendRow([
                        $player->groups->first()->name,
                        $team->name,
                        $player->first_name,
                        $player->last_name,
                        $player->gender,
                        $player->seasons->first()->pivot->grade,
                        $team->pivot->created_at->timezone(Auth::user()->settings->timeszone())->toDateTimeString(),
                    ]);
                }
            });
        });

        if (app()->environment('testing')) {
            echo $document->string('csv');
        } else {
            $document->download($format);
        }
    }

    public function exportQuizmasters(int $tournamentId, string $format, Excel $excel)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $quizmasters = $tournament->eligibleQuizmasters()
            ->with('user', 'group')
            ->get();

        $document = $excel->create($tournament->slug.'_quizmasters', function (LaravelExcelWriter $excel) use ($quizmasters, $tournament) {
            $excel->sheet('Quizmasters', function (LaravelExcelWorksheet $sheet) use ($quizmasters, $tournament) {

                $headers = [
                    'Group',
                    'First Name',
                    'Last Name',
                    'E-mail',
                    'Phone',
                    'Gender',
                    'Registered',
                ];

                if ($tournament->settings->shouldCollectShirtSizes()) {
                    $headers = array_merge($headers, [
                        'T-shirt Size',
                    ]);
                }

                if ($tournament->settings->shouldCollectQuizmasterPreferences()) {
                    $headers = array_merge($headers, [
                        'Quizzed At This Tournament Before',
                        'Times Quizzed At This Tournament',
                        'Games Quizzed This Season',
                        'Quizzing Interest (1-3)',
                        'Quizzing Frequency',
                    ]);
                }

                $sheet->appendRow($headers);

                /** @var TournamentQuizmaster $quizmasters */
                foreach ($quizmasters as $quizmaster) {
                    $data = [
                        $quizmaster->group_id == null ? '' : $quizmaster->group->name,
                        $quizmaster->first_name,
                        $quizmaster->last_name,
                        $quizmaster->email,
                        Html::formatPhone($quizmaster->phone),
                        $quizmaster->gender,
                        $quizmaster->created_at->timezone(Auth::user()->settings->timeszone())->toDateTimeString(),
                    ];

                    if ($tournament->settings->shouldCollectShirtSizes()) {
                        $data = array_merge($data, [
                            $quizmaster->shirt_size,
                        ]);
                    }

                    if ($tournament->settings->shouldCollectQuizmasterPreferences()) {
                        $data = array_merge($data, [
                            $quizmaster->quizzing_preferences->quizzedAtThisTournamentBefore() ? 'Y' : 'N',
                            $quizmaster->quizzing_preferences->timesQuizzedAtThisTournament(),
                            $quizmaster->quizzing_preferences->gamesQuizzedThisSeason(),
                            $quizmaster->quizzing_preferences->quizzingInterest() > 0 ? $quizmaster->quizzing_preferences->quizzingInterest() : '',
                            $quizmaster->quizzing_preferences->quizzingFrequency(),
                        ]);
                    }

                    $sheet->appendRow($data);
                }
            });
        });

        if (app()->environment('testing')) {
            echo $document->string('csv');
        } else {
            $document->download($format);
        }
    }
}
