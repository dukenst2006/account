<?php

namespace BibleBowl\Http\Controllers\Admin;

use DB;
use Html;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use BibleBowl\Http\Requests\AdminOnlyRequest;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Player;
use Input;
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

    public function export(Request $request, string $format, Excel $excel)
    {
        $players = Player::search($request)
            ->withSeasonCount()
            ->with('guardian', 'guardian.primaryAddress')
            ->groupBy('players.id')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->get();

        $excel->create('BibleBowlPlayers', function(LaravelExcelWriter $excel) use ($players) {

            $excel->sheet('Sheetname', function(LaravelExcelWorksheet $sheet) use ($players) {

                $sheet->appendRow([
                    'GUID',
                    'Last Name',
                    'First Name',
                    'Gender',
                    'Birthday',
                    'Seasons Played',
                    'Address One',
                    'Address Two',
                    'City',
                    'State',
                    'Zip Code',
                    'Guardian GUID',
                    'Guardian Last Name',
                    'Guardian First Name',
                    'Guardian Email',
                    'Guardian Phone',
                ]);

                $sheet->row(1, function(CellWriter $row) {
                    $row->setFontWeight('bold');
                });

                /** @var Player $player */
                foreach ($players as $player) {
                    $sheet->appendRow([
                        $player->guid,
                        $player->last_name,
                        $player->first_name,
                        $player->gender,
                        $player->birthday->toDateString(),
                        $player->seasonCount,
                        $player->guardian->primaryAddress->address_one,
                        $player->guardian->primaryAddress->address_two,
                        $player->guardian->primaryAddress->city,
                        $player->guardian->primaryAddress->state,
                        $player->guardian->primaryAddress->zip_code,
                        $player->guardian->guid,
                        $player->guardian->last_name,
                        $player->guardian->first_name,
                        $player->guardian->email,
                        Html::formatPhone($player->guardian->phone),
                    ]);
                }

            });

        })->download($format);
    }

    public function destroy(AdminOnlyRequest $request, $playerId)
    {
        $player = Player::findOrFail($playerId);
        $player->delete();

        return redirect('/admin/users/'.$player->guardian_id)->withFlashSuccess('Player has been deleted');
    }
}
