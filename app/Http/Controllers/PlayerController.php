<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Http\Requests\GuardianOnlyRequest;
use BibleBowl\Player;
use BibleBowl\Players\PlayerCreator;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

	/**
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('player.create');
	}

	/**
	 * @return mixed
	 */
	public function store(Request $request, PlayerCreator $playerCreator)
	{
		$this->validate($request, Player::validationRules());

		$player = $playerCreator->create(Auth::user(), $request->all());

		return redirect('/dashboard')->withFlashSuccess($player->full_name.' has been added');
	}

	/**
	 * @param GuardianOnlyRequest $request
	 *
	 * @return \Illuminate\View\View
	 */
	public function edit(GuardianOnlyRequest $request, $id)
	{
		return view('account.address.edit')
			->withPlayer(Player::findOrFail($id));
	}

	/**
	 * @param GuardianOnlyRequest 	$request
	 * @param                     	$id
	 *
	 * @return mixed
	 */
	public function update(GuardianOnlyRequest $request, $id)
	{
		$this->validate($request, Player::validationRules());

		Player::findOrFail($id)->update($request->all());

		return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
	}

}
