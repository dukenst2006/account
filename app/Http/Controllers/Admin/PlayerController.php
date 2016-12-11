<?php

namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Reporting\PlayerExporter;
use DB;
use Html;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use BibleBowl\Http\Requests\AdminOnlyRequest;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Player;
use Html;
use Input;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

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
