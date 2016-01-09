<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Reporting\MetricsRepository;

class ReportsController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function getGrowth(MetricsRepository $metrics)
    {
        return view('reports.growth', [
            'groupSummaryByProgram' => $metrics->historicalGroupSummaryByProgram(),
            'playerSummaryByProgram' => $metrics->historicalPlayerSummaryByProgram()
        ]);
    }
}
