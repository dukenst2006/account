<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminOnlyRequest;
use App\Http\Requests\Request;
use App\Player;
use App\Reporting\PlayerExporter;
use Input;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $players = Player::search($request)
            ->with('guardian')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->paginate(25);

        return view('/admin/players/index', [
            'players' => $players->appends(Input::only('q')),
        ]);
    }

    public function show($playerId)
    {
        $player = Player::findOrFail($playerId);

        return view('/admin/players/show', [
            'player'    => $player,
            'seasons'   => $player->seasons()->orderBy('id', 'desc')->get(),
        ]);
    }

    public function export(Request $request, string $format, PlayerExporter $exporter)
    {
        $players = Player::search($request)
            ->withSeasonCount()
            ->with('guardian', 'guardian.primaryAddress')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->get();
        $exporter->export('Players', $players)->download($format);
    }

    public function destroy(AdminOnlyRequest $request, $playerId)
    {
        $player = Player::findOrFail($playerId);
        $player->delete();

        return redirect('/admin/users/'.$player->guardian_id)->withFlashSuccess('Player has been deleted');
    }
}
