<?php namespace BibleBowl\Http\Controllers\Seasons;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupJoinRequest;
use BibleBowl\Http\Requests\PlayerGuardianOnlyRequest;
use BibleBowl\Http\Requests\SeasonRegistrationRequest;
use BibleBowl\Player;
use BibleBowl\Seasons\SeasonRegistrar;
use BibleBowl\Support\Cookies;
use Illuminate\View\View;
use Input;
use Session;

class PlayerRegistrationController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function findGroupToRegister()
	{
		return view('seasons.registration.register_find_group')
            ->with('familiarGroup', Session::getGroupToRegisterWith());
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function findGroupToJoin()
	{
		return view('seasons.registration.join_find_group')
            ->with('familiarGroup', Session::getGroupToRegisterWith());
	}

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister($group = null)
    {
		if (is_null($group) === false) {
			$group = Group::findOrFail($group);
		}

        return view('seasons.registration.register_form')
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

	/**
	 * @return \Illuminate\View\View
	 */
	public function getRegisterEdit($player)
	{
		$player = Player::findOrFail($player);

		return view('seasons.registration.edit')
			->withPlayer($player)
			->withSeason($player->seasons()->wherePivot('season_id', Session::season()->id)->first());
	}

	/**
	 * @return mixed
	 */
	public function postRegisterEdit(PlayerGuardianOnlyRequest $request, $player)
	{
		$this->validate($request, [
			'shirt_size' 	=> 'required',
			'grade'			=> 'required'
		]);

		$player = Player::findOrFail($player);
		$player->seasons()->updateExistingPivot(
			Session::season()->id,
			$request->only(['shirt_size', 'grade'])
		);

		return redirect('/dashboard')->withFlashSuccess('Registration has been updated');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getJoin($group)
	{
		return view('seasons.registration.join_form')
			->withGroup(Group::findOrFail($group))
			->withPlayers(Auth::user()->players()->registeredWithNBBOnly(Session::season())->get());
	}

	public function postJoin(GroupJoinRequest $request, GroupRegistrar $registrar, $group)
	{
		$this->validate($request, $request->rules());

		$group = Group::findOrFail($group);

		$registrar->register(Session::season(), $group, array_keys($request->get('player')));

		return redirect('/dashboard')->withFlashSuccess('Your player(s) have joined a group!');
	}

	/**
	 * Remember the group the user is trying to register for
	 */
	public function rememberGroup($guid)
	{
        Session::setGroupToRegisterWith($guid);

		return redirect('/');
	}

	public static function viewBindings()
	{
		\View::creator('seasons.registration.search_group', function (View $view) {
			$searchResults = null;
			if (Input::has('q')) {
				$searchResults = Group::active()->where('name', 'LIKE', '%'.Input::get('q').'%')->get();
			}
			$view->with('searchResults', $searchResults);
		});

		\View::creator('seasons.registration.register_form', function (View $view) {
			$season = Session::season();
			$view->with('players', Auth::user()
				->players()
				->notRegisteredWithNBB($season, Auth::user())
				->get()
			);
		});
	}

}
