<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupCreator;
use BibleBowl\Http\Requests\GroupCreationRequest;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupEditRequest;
use BibleBowl\Program;
use BibleBowl\Reporting\MetricsRepository;
use Session;

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
