<?php

namespace BibleBowl\Http\Controllers\Tournaments\Admin;

use Auth;
use BibleBowl\Competition\Tournaments\ShirtSizeExporter;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\ParticipantType;
use BibleBowl\Player;
use BibleBowl\Season;
use BibleBowl\Spectator;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use Carbon\Carbon;
use DB;
use Html;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class TournamentExportController extends Controller
{
    public function exportTeams(int $tournamentId, string $format, Excel $excel)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        // get some info about them this season
        $players = $tournament->eligiblePlayers()
            ->select(
                'players.*',
                'groups.name AS group_name',
                'player_season.grade AS player_grade',
                'teams.name AS team_name',
                'team_player.created_at AS added_to_team'
            )
            ->join('player_season', function (JoinClause $join) use ($tournament) {
                $join->on('player_season.player_id', '=', 'players.id');
                $join->on('player_season.season_id', '=', DB::raw($tournament->season_id));
            })
            ->join('team_sets', 'team_sets.tournament_id', '=', DB::raw($tournament->id))
            ->join('groups', 'groups.id', '=', 'team_sets.group_id')
            ->join('team_player', 'team_player.player_id', '=', 'players.id')
            ->join('teams', 'teams.team_set_id', '=', 'team_sets.id')
            ->with([
                'events' => function (BelongsToMany $q) use ($tournament) {
                    $q->where('events.tournament_id', $tournament->id);
                },
            ])
            ->groupBy('players.id')
            ->orderBy('groups.name', 'ASC')
            ->orderBy('teams.name', 'ASC')
            ->get();

        // we're including optional player events here (Quote Bee, Individual Tournament, etc.)
        // since there's no other export that includes all of the player's information
        $optionalPlayerEvents = $tournament->events()->with('type')
            ->byParticipantType(ParticipantType::PLAYER)
            ->withOptionalParticipation()
            ->get();

        $document = $excel->create($tournament->slug.'_teams', function (LaravelExcelWriter $excel) use ($players, $optionalPlayerEvents) {
            $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players, $optionalPlayerEvents) {
                $headers = [
                    'Group',
                    'Team',
                    'First Name',
                    'Last Name',
                    'Gender',
                    'Grade',
                    'Added To Team',
                ];
                foreach ($optionalPlayerEvents as $optionalPlayerEvent) {
                    $headers[] = $optionalPlayerEvent->type->name;
                }
                $sheet->appendRow($headers);

                /** @var Player $player */
                foreach ($players as $player) {
                    $data = [
                        $player->group_name,
                        $player->team_name,
                        $player->first_name,
                        $player->last_name,
                        $player->gender,
                        $player->player_grade,
                        (new Carbon($player->added_to_team))->timezone(Auth::user()->settings->timeszone())->toDateTimeString(),
                    ];

                    foreach ($optionalPlayerEvents as $optionalPlayerEvent) {
                        $hasUnpaidFees = $player->events->filter(function ($item) use ($optionalPlayerEvent) {
                            return $item->id == $optionalPlayerEvent->id && $item->pivot->receipt_id != null;
                        })->count() == 0;
                        if ($player->events->contains($optionalPlayerEvent->id) && $hasUnpaidFees === false) {
                            $data[] = 'Y';
                        } else {
                            $data[] = 'N';
                        }
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

    public function exportPlayers(Request $request, int $tournamentId, string $format, Excel $excel)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $playerQuery = $tournament->eligiblePlayers()
            ->select(
                'players.*',
                'groups.name AS group_name',
                'player_season.grade AS player_grade',
                'player_season.shirt_size AS player_shirt_size'
            )
            ->with('guardian', 'guardian.primaryAddress')

            // join on groups so we can order by group name
            ->join('player_season', 'player_season.player_id', '=', 'players.id')
            ->join('groups', 'groups.id', '=', 'player_season.group_id')
            ->where('player_season.season_id', $tournament->season->id)
            ->orderBy('groups.name', 'ASC')

            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC');

        if ($request->has('grade')) {
            $playerQuery->whereHas('seasons', function (Builder $q) use ($request, $tournament) {
                $q->where('seasons.id', $tournament->season_id)
                    ->where('player_season.grade', $request->get('grade'));
            });
        }

        $players = $playerQuery->get();

        $document = $excel->create($tournament->slug.'_players', function (LaravelExcelWriter $excel) use ($players, $tournament) {
            $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players, $tournament) {
                $headers = [
                    'Group',
                    'First Name',
                    'Last Name',
                    'Gender',
                    'Birthday',
                    'Grade',
                ];

                if ($tournament->settings->shouldCollectShirtSizes()) {
                    $headers = array_merge($headers, [
                        'T-shirt Size',
                    ]);
                }

                $headers = array_merge($headers, [
                    'Address One',
                    'Address Two',
                    'City',
                    'State',
                    'Zip Code',
                    'Guardian Last Name',
                    'Guardian First Name',
                    'Guardian Email',
                    'Guardian Phone',
                ]);

                $sheet->appendRow($headers);

                /** @var Player $player */
                foreach ($players as $player) {
                    $data = [
                        $player->group_name,
                        $player->last_name,
                        $player->first_name,
                        $player->gender,
                        $player->birthday->toDateString(),
                        $player->player_grade,
                    ];

                    if ($tournament->settings->shouldCollectShirtSizes()) {
                        $data = array_merge($data, [
                            $player->player_shirt_size,
                        ]);
                    }

                    $data = array_merge($data, [
                        $player->guardian->primaryAddress->address_one,
                        $player->guardian->primaryAddress->address_two,
                        $player->guardian->primaryAddress->city,
                        $player->guardian->primaryAddress->state,
                        $player->guardian->primaryAddress->zip_code,
                        $player->guardian->last_name,
                        $player->guardian->first_name,
                        $player->guardian->email,
                        Html::formatPhone($player->guardian->phone),
                    ]);
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

    public function exportQuizmasters(int $tournamentId, string $format, Excel $excel)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $quizmasters = $tournament->eligibleQuizmasters()
            ->with('user', 'group', 'registeredBy')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
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
                $headers = array_merge($headers, [
                    'Registered',
                    'Registered By',
                ]);

                $sheet->appendRow($headers);

                /** @var TournamentQuizmaster $quizmasters */
                foreach ($quizmasters as $quizmaster) {
                    $data = [
                        $quizmaster->hasGroup() ? $quizmaster->group->name : '',
                        $quizmaster->first_name,
                        $quizmaster->last_name,
                        $quizmaster->email,
                        Html::formatPhone($quizmaster->phone),
                        $quizmaster->gender,
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

                    $data = array_merge($data, [
                        $quizmaster->created_at->timezone(Auth::user()->settings->timeszone())->toDateTimeString(),
                        $quizmaster->wasRegisteredByHeadCoach() ? $quizmaster->registeredBy->full_name : '',
                    ]);

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

    public function exportTshirts(int $tournamentId, string $format, ShirtSizeExporter $shirtSizeExporter)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $document = $shirtSizeExporter->export($tournament);

        if (app()->environment('testing')) {
            echo $document->string('csv');
        } else {
            $document->download($format);
        }
    }

    public function exportSpectators(int $tournamentId, string $format, Excel $excel)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $spectators = $tournament->eligibleSpectators()
            ->with('minors', 'group', 'address')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->get();

        // determine the max number of minors so we can
        // have the appropriate number of columns
        $maxMinors = 0;
        foreach ($spectators as $spectator) {
            if ($spectator->isFamily()) {
                $minorCount = $spectator->minors->count();
                if ($minorCount > $maxMinors) {
                    $maxMinors = $minorCount;
                }
            }
        }

        $document = $excel->create($tournament->slug.'_spectators', function (LaravelExcelWriter $excel) use ($spectators, $tournament, $maxMinors) {
            $excel->sheet('Spectators', function (LaravelExcelWorksheet $sheet) use ($spectators, $tournament, $maxMinors) {
                $headers = [
                    'Group',
                    'First Name',
                    'Last Name',
                    'E-mail',
                    'Phone',
                    'Gender',
                ];
                if ($tournament->settings->shouldCollectShirtSizes()) {
                    $headers = array_merge($headers, [
                        'T-shirt Size',
                    ]);
                }

                $headers = array_merge($headers, [
                    'Spouse Name',
                    'Spouse Gender',
                ]);
                if ($tournament->settings->shouldCollectShirtSizes()) {
                    $headers = array_merge($headers, [
                        'Spouse T-shirt Size',
                    ]);
                }

                // add appropriate columns for each minor
                if ($maxMinors > 0) {
                    $minorColumns = [];
                    for ($x = 1; $x <= $maxMinors; $x++) {
                        $minorColumns = array_merge($minorColumns, [
                            'Minor '.$x.' Name',
                            'Minor '.$x.' Age',
                            'Minor '.$x.' Gender',
                        ]);
                        if ($tournament->settings->shouldCollectShirtSizes()) {
                            $minorColumns = array_merge($minorColumns, [
                                'Minor '.$x.' T-shirt Size',
                            ]);
                        }
                    }
                    if (count($minorColumns) > 0) {
                        array_unshift($minorColumns, 'Minors');
                        $headers = array_merge($headers, $minorColumns);
                    }
                }

                $headers = array_merge($headers, [
                    'Registered',
                    'Registered By',
                ]);

                $sheet->appendRow($headers);

                /** @var Spectator $spectators */
                foreach ($spectators as $spectator) {
                    $data = [
                        $spectator->hasGroup() ? $spectator->group->name : '',
                        $spectator->first_name,
                        $spectator->last_name,
                        $spectator->email,
                        Html::formatPhone($spectator->phone),
                        $spectator->gender,
                    ];

                    if ($tournament->settings->shouldCollectShirtSizes()) {
                        $data = array_merge($data, [
                            $spectator->shirt_size,
                        ]);
                    }

                    $data = array_merge($data, [
                        $spectator->spouse_first_name,
                        $spectator->spouse_gender,
                    ]);

                    if ($tournament->settings->shouldCollectShirtSizes()) {
                        $data = array_merge($data, [
                            $spectator->spouse_shirt_size,
                        ]);
                    }

                    // add appropriate minor data
                    if ($maxMinors > 0) {
                        $data = array_merge($data, [
                            $spectator->minors->count(),
                        ]);
                        foreach ($spectator->minors as $minor) {
                            $data = array_merge($data, [
                                $minor->name,
                                $minor->age,
                                $minor->gender,
                            ]);

                            if ($tournament->settings->shouldCollectShirtSizes()) {
                                $data = array_merge($data, [
                                    $minor->shirt_size,
                                ]);
                            }
                        }
                    }

                    $data = array_merge($data, [
                        $spectator->created_at->timezone(Auth::user()->settings->timeszone())->toDateTimeString(),
                        $spectator->wasRegisteredByHeadCoach() ? $spectator->registeredBy->full_name : '',
                    ]);

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
