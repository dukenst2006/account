<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Groups\PlayerInactiveToggleRequest;
use App\Player;
use Session;

class PlayerController extends Controller
{
    /**
     * @param PlayerInactiveToggleRequest $request
     * @param                             $player
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
     * @param PlayerInactiveToggleRequest $request
     * @param                             $player
     *
     * @return mixed
     */
    public function deactivate(PlayerInactiveToggleRequest $request, $player)
    {
        $player = Player::findOrFail($player);
        $player->deactivate(Session::season());

        return redirect('/roster')->withFlashSuccess($player->full_name.' is now inactive');
    }
}
