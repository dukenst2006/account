<?php

namespace App\Http\Controllers;

use App\Reporting\MetricsRepository;
use App\Reporting\PlayerMetricsRepository;
use App\Role;
use App\Tournament;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Redirect;
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
     * Force users to login.
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
    public function index(Request $request)
    {
        // accept any pending invitations
        if (Session::hasPendingInvitation()) {
            DB::beginTransaction();
            $invitation = Session::pendingInvitation();
            $invitationType = app($invitation->type);
            $invitationType->accept(Auth::user(), $invitation);
            Session::setPendingInvitation();
            DB::commit();

            return redirect('dashboard')->withFlashSuccess('Invitation has been accepted');
        }

        // if the user should be redirected, lets do that now
        if (Session::redirectToAfterAuth() != null) {
            $redirectTo = Session::redirectToAfterAuth();
            Session::setRedirectToAfterAuth(null);

            return redirect($redirectTo);
        }

        $season = Session::season();
        $view = view('dashboard');

        if (Auth::user()->isA(Role::HEAD_COACH)) {
            $view->with('rosterOverview', [
                'playerStats' => $this->playerMetrics->playerStats(
                    $season,
                    Session::group()
                ),
                'familyCount' => Session::group()->guardians($season)->count(),
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
                            },
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
                'groupCount'       => $metrics->groupCount($season),
                'playerCount'      => $playerCount,
                'averageGroupSize' => $metrics->averageGroupSize($playerCount),
            ]);
        });

        \View::creator('dashboard.tournaments', function (View $view) {
            $season = Session::season();
            $view->with(
                'tournaments',
                Tournament::visible($season, Session::group()->program_id)->get()
            );
        });
    }
}
