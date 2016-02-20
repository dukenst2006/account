<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Reporting\PlayerMetricsRepository;
use Illuminate\View\View;
use Session;

class DashboardController extends Controller
{
    private $playerMetrics;

    public function __construct(PlayerMetricsRepository $playerMetrics)
    {
        $this->playerMetrics = $playerMetrics;

        $this->middleware('requires.setup');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $view = view('dashboard');
        if (Auth::user()->hasRole(\BibleBowl\Role::HEAD_COACH)) {
            $view->with('rosterOverview', [
                'playerStats' => $this->playerMetrics->playerStats(
                    Session::season(),
                    Session::group()
                )
            ]);
            $view->with('playersPendingPayment',  Session::group()->players()->pendingRegistrationPayment()->get());
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
