<?php namespace BibleBowl\Http\Controllers\Teams;

use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\TeamSetGroupOnlyRequest;
use BibleBowl\Http\Requests\TeamGroupOnlyRequest;
use BibleBowl\Team;
use DB;

class TeamController extends Controller
{

	/**
	 * @param  	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function createTeam(TeamSetGroupOnlyRequest $request)
	{
		$request->merge([
			'team_set_id' => $request->route('teamsets')
		]);

		$this->validate($request, [
			'name' => 'required'
		]);

		$team = Team::create($request->except('_token'));

		return response()->json($team);
	}

	/**
	 * @param  	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function deleteTeam(TeamGroupOnlyRequest $request)
	{
		$team = Team::findOrFail($request->route('team'));
		$team->delete();

		return response()->json();
	}

	/**
	 * @param  	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function addPlayer(TeamGroupOnlyRequest $request, $id)
	{
		$request->team()->players()->attach($request->get('playerId'));

		return response()->json();
	}

	/**
	 * @param  	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function removePlayer(TeamGroupOnlyRequest $request, $id)
	{
		$request->team()->players()->detach($request->get('playerId'));

		return response()->json();
	}

}
