<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Ability;
use Bouncer;
use BibleBowl\Reporting\MetricsRepository;
use BibleBowl\Reporting\PlayerMetricsRepository;
use BibleBowl\Role;
use Illuminate\View\View;
use Session;
use Redirect;

class DashboardController extends Controller
{
    private $playerMetrics;

    public function __construct(PlayerMetricsRepository $playerMetrics)
    {
        $this->playerMetrics = $playerMetrics;

        $this->middleware('requires.setup');
    }

    /**
     * Force users to login
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function root()
    {
        if (Auth::guest()) {
            return Redirect::to('login');
        }

        return Redirect::to('dashboard');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        // if the user should be redirected, lets do that now
        if (Session::redirectToAfterAuth() != null) {
            $redirectTo = Session::redirectToAfterAuth();
            Session::setRedirectToAfterAuth(null);
            return redirect($redirectTo);
        }
        
        $season = Session::season();
        $view = view('dashboard');

        if (Auth::user()->is(Role::HEAD_COACH)) {
            $view->with('rosterOverview', [
                'playerStats' => $this->playerMetrics->playerStats(
                    $season,
                    Session::group()
                )
            ]);
            $view->with('playersPendingPayment', Session::group()->players()->pendingRegistrationPayment($season)->get());
        }

        return $view;
    }

    public static function viewBindings()
    {
        \View::creator('dashboard.guardian-children', function (View $view) {
            $season = Session::season();
            $groupToRegisterWith = Session::getGroupToRegisterWith();
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
            )
            ->with('season', $season)
            ->with('hasGroupToRegisterWith', $groupToRegisterWith != null)
            ->with('groupToRegisterWith', $groupToRegisterWith);
        });

        \View::creator('dashboard.season-overview', function (View $view) {
            $season = Session::season();
            /** @var MetricsRepository $metrics */
            $metrics = app(MetricsRepository::class);
            $playerCount = $metrics->playerCount($season);
            $view->with([
                'groupCount' => $metrics->groupCount($season),
                'playerCount' => $playerCount,
                'averageGroupSize' =>  $metrics->averageGroupSize($playerCount),
            ]);
        });
    }
}
