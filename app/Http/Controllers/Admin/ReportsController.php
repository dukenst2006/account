<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Request;
use App\Player;
use App\Program;
use App\RegistrationSurveyQuestion;
use App\Reporting\FinancialsRepository;
use App\Reporting\GroupMetricsRepository;
use App\Reporting\MetricsRepository;
use App\Reporting\PlayerExporter;
use App\Reporting\PlayerMetricsRepository;
use App\Reporting\SurveyMetricsRepository;
use App\Season;
use Html;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class ReportsController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getGrowth(MetricsRepository $metrics)
    {
        return view('admin.reports.growth', [
            'groupSummaryByProgram'  => $metrics->historicalGroupSummaryByProgram(),
            'playerSummaryByProgram' => $metrics->historicalPlayerSummaryByProgram(),
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getSeason(Request $request, PlayerMetricsRepository $playerMetrics, GroupMetricsRepository $groupMetrics)
    {
        $seasons = Season::orderBy('id', 'DESC')->get();
        $currentSeason = $request->has('seasonId') ? Season::findOrFail($request->get('seasonId')) : $seasons->first();

        return view('admin.reports.season', [
            'currentSeason'     => $currentSeason,
            'seasons'           => $seasons,
            'playerCount'       => $currentSeason->players()->count(),
            'playerStats'       => $playerMetrics->playerStats($currentSeason),
            'bySchoolSegment'   => $playerMetrics->bySchoolSegment($currentSeason),
            'groupStats'        => $groupMetrics->groupStats($currentSeason),
        ]);
    }

    public function exportPlayers(Request $request, PlayerExporter $exporter, int $programId)
    {
        $currentSeason = $request->has('seasonId') ? Season::findOrFail($request->get('seasonId')) : Season::current()->first();

        if ($request->has('sponsors')) {
            $players = Player::active($currentSeason)
                ->with([
                    'guardian',
                    'guardian.primaryAddress',
                    'seasons' => function ($q) use ($currentSeason) {
                        $q->where('seasons.id', $currentSeason->id);
                    },
                ])
                ->whereHas('groups', function ($q) use ($programId, $currentSeason) {
                    $q->wherePivot('season_id', $currentSeason->id);
                    $q->where('program_id', $programId);
                })
                ->orderBy('last_name', 'ASC')
                ->orderBy('first_name', 'ASC')
                ->get();

            $excel = app(Excel::class);
            $filename = $currentSeason->name.'_players_for_sponsors';
            $document = $excel->create($filename, function (LaravelExcelWriter $excel) use ($players) {
                $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players) {
                    $sheet->appendRow([
                        'Last Name',
                        'First Name',
                        'Grade',
                        'Email',
                        'Phone',
                        'Address One',
                        'Address Two',
                        'City',
                        'State',
                        'Zip Code',
                        'Group',
                        'Group GUID',
                    ]);

                    /** @var Player $player */
                    foreach ($players as $player) {
                        $sheet->appendRow([
                            $player->last_name,
                            $player->first_name,
                            $player->seasons->first()->pivot->grade,
                            $player->guardian->email,
                            Html::formatPhone($player->guardian->phone),
                            $player->guardian->primaryAddress->address_one,
                            $player->guardian->primaryAddress->address_two,
                            $player->guardian->primaryAddress->city,
                            $player->guardian->primaryAddress->state,
                            $player->guardian->primaryAddress->zip_code,
                        ]);
                    }
                });
            });
        } else {
            $players = Player::active($currentSeason)
                ->withSeasonCount()
                ->with('guardian', 'guardian.primaryAddress')
                ->whereHas('groups', function ($q) use ($programId) {
                    $q->where('program_id', $programId);
                })
                ->orderBy('last_name', 'ASC')
                ->orderBy('first_name', 'ASC')
                ->get();

            $document = $exporter->export($currentSeason->name.'_players', $players);
        }

        if (app()->environment('testing')) {
            echo $document->string('csv');
        } else {
            $document->download('csv');
        }
    }

    public function exportMemoryMaster(Request $request, PlayerExporter $exporter, int $programId)
    {
        $currentSeason = $request->has('seasonId') ? Season::findOrFail($request->get('seasonId')) : Season::current()->first();

        $program = Program::findOrFail($programId);
        $players = Player::achievedMemoryMaster($currentSeason, $programId)
            ->withSeasonCount()
            ->with('guardian', 'guardian.primaryAddress')
            ->with([
                'groups' => function ($q) use ($currentSeason) {
                    $q->where('player_season.season_id', $currentSeason->id);
                },
            ])
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->get();

        $document = $exporter->export(
            $currentSeason->name.'_'.$program->name.'_memory-master-achievers',
            $players,
            function (array $headers) {
                array_unshift($headers, 'Group', 'Group GUID');

                return $headers;
            },
            function (Player $player, array $playerData) {
                $group = $player->groups->first();
                array_unshift($playerData, $group->name, $group->guid);

                return $playerData;
            }
        );

        if (app()->environment('testing')) {
            echo $document->string('csv');
        } else {
            $document->download('csv');
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getFinancials(Request $request, FinancialsRepository $financialsRepository)
    {
        return view('admin.reports.financials', [
            'invoiceItemSummary' => $financialsRepository->invoiceItemSummary(),
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getRegistrationSurveys(Request $request, SurveyMetricsRepository $metrics)
    {
        $seasons = Season::orderBy('id', 'DESC')->get();
        $currentSeason = $request->has('seasonId') ? Season::findOrFail($request->get('seasonId')) : $seasons->first();

        $questions = [];
        foreach (RegistrationSurveyQuestion::orderBy('order')->get() as $question) {
            $questions[$question->id] = [
                'question'  => $question,
                'metrics'   => $metrics->byQuestion($question, $currentSeason),
            ];
        }

        return view('admin.reports.registration-surveys', [
            'currentSeason' => $currentSeason,
            'seasons'       => $seasons,
            'questions'     => $questions,
        ]);
    }
}
