<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Request;
use App\Player;
use App\RegistrationSurveyQuestion;
use App\Reporting\FinancialsRepository;
use App\Reporting\GroupMetricsRepository;
use App\Reporting\MetricsRepository;
use App\Reporting\PlayerExporter;
use App\Reporting\PlayerMetricsRepository;
use App\Reporting\SurveyMetricsRepository;
use App\Season;

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
            'playerStats'       => $playerMetrics->playerStats($currentSeason),
            'bySchoolSegment'   => $playerMetrics->bySchoolSegment($currentSeason),
            'groupStats'        => $groupMetrics->groupStats($currentSeason),
        ]);
    }

    public function exportMemoryMaster(Request $request, PlayerExporter $exporter)
    {
        $currentSeason = $request->has('seasonId') ? Season::findOrFail($request->get('seasonId')) : Season::current()->first();

        $players = Player::achievedMemoryMaster($currentSeason)
            ->withSeasonCount()
            ->with('guardian', 'guardian.primaryAddress')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->get();

        echo $exporter->export($currentSeason->name.'_memory-master-achievers', $players)->string('csv');
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
