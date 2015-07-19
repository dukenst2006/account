<?php namespace BibleBowl\Http\Controllers\Seasons;

use BibleBowl\Group;
use BibleBowl\Seasons\SeasonRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GuardianOnlyRequest;
use BibleBowl\Http\Requests\SeasonRegistrationRequest;
use BibleBowl\Player;
use Session;

class PlayerRegistrationController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister($groupId = null)
    {
		$group = null;
		if (is_null($groupId) === false) {
			$group = Group::findOrFail($groupId);
		}

        return view('seasons.player-registration')
			->withGroup($group);
    }

	/**
	 * @return mixed
	 */
	public function postRegister(SeasonRegistrationRequest $request, SeasonRegistrar $registrar, $groupId = null)
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

        $registrar->register(Session::season(), $seasonData, $groupId);

		return redirect('/dashboard')->withFlashSuccess('Your player(s) have been registered!');
	}

	/**
	 * @param GuardianOnlyRequest $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit(GuardianOnlyRequest $request, $id)
	{
		return view('player.edit')
			->withPlayer(Player::findOrFail($id));
	}

}
