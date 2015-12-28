<?php namespace BibleBowl\Http\Controllers\Teams;

use Auth;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\TeamSetGroupOnlyRequest;
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

		return redirect('/teamsets')->withFlashSuccess($teamSet->name.' has been added');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function show(TeamSetGroupOnlyRequest $request, $id)
	{
		$teamSet = TeamSet::findOrFail($id);
		return view('teamset.show')
				->with('teamSet', $teamSet)
				->withPlayers(Session::group()->players()->notOnTeamSet($teamSet)->get());
	}

	/**
	 * @param  	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function update(TeamSetGroupOnlyRequest $request, $id)
	{
		$teamSet = TeamSet::findOrFail($id);
		$teamSet->update($request->only('name'));

		return response()->json();
	}

	/**
	 * @param $id
	 */
	public function pdf(TeamSetGroupOnlyRequest $request, $id)
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
