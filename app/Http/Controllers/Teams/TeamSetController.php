<?php namespace BibleBowl\Http\Controllers\Teams;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\TeamSet;
use Illuminate\Http\Request;
use Session;

class TeamSetController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('teamset.index')
				->with('teamSets', Session::group()->teamSets);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('teamset.create');
	}

	/**
	 * @return mixed
	 */
	public function store(Request $request)
	{
		$request->merge([
			'group_id' 	=> Session::group()->id,
			'season_id' => Session::season()->id
		]);

		$this->validate($request, TeamSet::validationRules());

		$teamSet = TeamSet::create($request->all());

		return redirect('/team')->withFlashSuccess($teamSet->name.' has been added');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function show($id)
	{
		return view('teamset.show')
				->with('teamSet', TeamSet::findOrFail($id));
	}

	/**
	 * @param $id
	 */
	public function download($id)
	{
		/** @var TeamSet $teamSet */
		$teamSet = TeamSet::with('teams.players')->findOrFail($id);

		/** @var \Barryvdh\DomPDF\PDF $pdf */
		$pdf = app('dompdf.wrapper');
		$pdf->loadView('teamset.pdf', [
			'teamSet' 		=> $teamSet,
			'user'			=> Auth::user(),
			'lastUpdated'	=> $teamSet->updated_at->timezone(Auth::user()->settings->timeszone())
		]);
		return $pdf->stream();
	}

}
