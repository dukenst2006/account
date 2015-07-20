<?php namespace BibleBowl\Http\Controllers;

use Auth;
use Illuminate\View\View;
use Session;

class DashboardController extends Controller
{
	public function __construct()
	{
		$this->middleware('requires.setup');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('dashboard');
	}

	public static function viewBindings()
	{
		\View::creator('dashboard.guardian_children', function (View $view) {
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
