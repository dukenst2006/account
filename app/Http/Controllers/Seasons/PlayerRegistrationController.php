<?php namespace BibleBowl\Http\Controllers\Seasons;

use Input;
use BibleBowl\Group;
use BibleBowl\Seasons\SeasonRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\SeasonRegistrationRequest;
use BibleBowl\Player;
use Session;

class PlayerRegistrationController extends Controller
{

	/**
	 * @return $this
	 */
	public function findGroupToRegister()
	{
		$searchResults = null;
		if (Input::has('q')) {
			$searchResults = Group::where('name', 'LIKE', '%'.Input::get('q').'%')->get();
		}

		return view('seasons.registration.register_group')
			->with('searchResults', $searchResults);
	}

	/**
	 * @return $this
	 */
	public function findGroupToJoin()
	{
		$searchResults = null;
		if (Input::has('q')) {
			$searchResults = Group::where('name', 'LIKE', '%'.Input::get('q').'%')->get();
		}

		return view('seasons.registration.join_group')
			->with('searchResults', $searchResults);
	}

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister($group = null)
    {
		$group = null;
		if (is_null($group) === false) {
			$group = Group::findOrFail($group);
		}

        return view('seasons.registration.form')
			->withGroup($group);
    }

	/**
	 * @return mixed
	 */
	public function postRegister(SeasonRegistrationRequest $request, SeasonRegistrar $registrar, $group = null)
	{
		$this->validate($request, $request->rules());

		// map the POSTed data to the season data required
		$seasonData = [];
		foreach ($request->get('player') as $playerId => $playerData) {
			if (isset($playerData['register']) && $playerData['register'] == 1) {
				$seasonData[$playerId] = [
					'grade' 		=> $playerData['grade'],
					'shirt_size' 	=> $playerData['shirtSize']
				];
			}
		}

        $registrar->register(Session::season(), $seasonData, $group);

		return redirect('/dashboard')->withFlashSuccess('Your player(s) have been registered!');
	}

}
