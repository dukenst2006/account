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
	public function store(TeamSetGroupOnlyRequest $request)
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
	public function update(TeamGroupOnlyRequest $request)
	{
		$request->team()->update([
			'name' => $request->input('name')
		]);

		return response()->json();
	}

	/**
	 * @param  	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function destroy(TeamGroupOnlyRequest $request)
	{
		$request->team()->delete();

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
	 * @param   $id
	 *
	 * @return mixed
	 */
	public function removePlayer(TeamGroupOnlyRequest $request, $id)
	{
		$request->team()->players()->detach($request->get('playerId'));

		return response()->json();
	}

	/**
	 * Update the order of players on a team
	 *
	 * @param  	$request
	 * @param   $id
	 *
	 * @return mixed
	 */
	public function updateOrder(TeamGroupOnlyRequest $request, $id)
	{
		DB::transaction(function () use($request) {
			foreach($request->input('sortOrder') as $index => $playerId) {
				$request->team()->players()->updateExistingPivot($playerId, [
					'order' => $index
				]);
			}
		});

		return response()->json();
	}

}
