<?php namespace BibleBowl\Http\Controllers\Groups;

use Session;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\Groups\PlayerInactiveToggleRequest;
use BibleBowl\Player;

class PlayerController extends Controller
{

	/**
	 * @param PlayerInactiveToggleRequest 	$request
	 * @param                     			$player
	 *
	 * @return mixed
	 */
	public function activate(PlayerInactiveToggleRequest $request, $player)
	{
		$player = Player::findOrFail($player);
		$player->activate(Session::season());

		return redirect('/roster')->withFlashSuccess($player->full_name.' is now active');
	}

	/**
	 * @param PlayerInactiveToggleRequest 	$request
	 * @param                     			$player
	 *
	 * @return mixed
	 */
	public function deactivate(PlayerInactiveToggleRequest $request, $player)
	{
		$player = Player::findOrFail($player);
		$player->deactive(Session::season());

		return redirect('/roster')->withFlashSuccess($player->full_name.' is now inactive');
	}

}
