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

        return view('seasons.player-registration.register')
			->withGroup($group);
    }

	/**
	 * @return mixed
	 */
	public function postRegister(SeasonRegistrationRequest $request, SeasonRegistrar $registrar)
	{
		$this->validate($request, $request->rules());

        $registrar->register(Session::season(), $request->get('player'));

		return redirect('/dashboard')->withFlashSuccess('Your players have been registered!');
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
