<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Permission;
use BibleBowl\Program;
use BibleBowl\Reporting\MetricsRepository;
use BibleBowl\Reporting\PlayerMetricsRepository;
use BibleBowl\Role;
use Illuminate\View\View;
use Session;

class DashboardController extends Controller
{
    private $playerMetrics;

    private $metrics;

    public function __construct(PlayerMetricsRepository $playerMetrics, MetricsRepository $metrics)
    {
        $this->playerMetrics = $playerMetrics;
        $this->metrics = $metrics;

        $this->middleware('requires.setup');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $season = Session::season();
        $view = view('dashboard');

        if (Auth::user()->hasRole(Role::HEAD_COACH)) {
            $view->with('rosterOverview', [
                'playerStats' => $this->playerMetrics->playerStats(
                    $season,
                    Session::group()
                )
            ]);
            $view->with('playersPendingPayment',  Session::group()->players()->pendingRegistrationPayment()->get());
        }

        if (Auth::user()->can(Permission::VIEW_REPORTS)) {
            $view->with('seasonOverview', [
                'groupCount' => $this->metrics->groupCount($season),
                'playerCount' => $this->metrics->playerCount($season),
                'averageGroupSize' => $this->metrics->averageGroupSize($season)
            ]);
        }

        return $view;
    }

    public static function viewBindings()
    {
        \View::creator('dashboard.guardian-children', function (View $view) {
            $season = Session::season();
            $view->with(
                'children',
                Auth::user()->players()
                    // eager load the current season/group
                    ->with(
                        [
                            'seasons' => function ($q) use ($season) {
                                $q->where('seasons.id', $season->id);
                            },
                            'groups' => function ($q) use ($season) {
                                $q->wherePivot('season_id', $season->id);
                            }
                        ]
                    )
                    ->get()
            );
        });
    }
}
