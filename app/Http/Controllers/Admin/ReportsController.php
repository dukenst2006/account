<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Http\Requests\Request;
use BibleBowl\Program;
use BibleBowl\Reporting\GroupMetricsRepository;
use BibleBowl\Reporting\MetricsRepository;
use BibleBowl\Reporting\PlayerMetricsRepository;
use BibleBowl\Reporting\SurveyMetricsRepository;
use BibleBowl\Season;
use BibleBowl\RegistrationSurveyQuestion;

class ReportsController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function getGrowth(MetricsRepository $metrics)
    {
        return view('admin.reports.growth', [
            'groupSummaryByProgram' => $metrics->historicalGroupSummaryByProgram(),
            'playerSummaryByProgram' => $metrics->historicalPlayerSummaryByProgram()
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
                'metrics'   => $metrics->byQuestion($question, $currentSeason)
            ];
        }

        return view('admin.reports.registration-surveys', [
            'currentSeason' => $currentSeason,
            'seasons'       => $seasons,
            'questions'     => $questions
        ]);
    }
}
