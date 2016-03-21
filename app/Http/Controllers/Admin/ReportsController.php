<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Http\Requests\Request;
use BibleBowl\Reporting\MetricsRepository;
use BibleBowl\Reporting\PlayerMetricsRepository;
use BibleBowl\Season;

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
    public function getPlayers(Request $request, PlayerMetricsRepository $metrics)
    {
        $seasons = Season::orderBy('id', 'DESC')->get();
        $currentSeason = $request->has('seasonId') ? Season::findOrFail($request->get('seasonId')) : $seasons->first();
        return view('admin.reports.players', [
            'currentSeason' => $currentSeason,
            'seasons'       => $seasons,
            'playerStats'   => $metrics->playerStats($currentSeason)
        ]);
    }
}
