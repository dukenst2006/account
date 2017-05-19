<?php

namespace App\Http\Controllers\Api;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Player;
use App\Season;
use Html;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class PlayerController extends Controller
{
    public function exportPlayers(Request $request)
    {
        $season = $request->has('season') ? Season::where('name', $request->get('season'))->firstOrFail() : Season::current()->first();

        $players = Player::active($season)
            ->with([
                'groups.meetingAddress',
                'seasons' => function ($q) use ($season) {
                    $q->where('seasons.id', $season->id);
                },
                'groups' => function ($q) use ($season) {
                    $q->wherePivot('season_id', $season->id);
                },
                'groups.program',
            ])
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->get();

        $excel = app(Excel::class);
        $filename = $season->name.'_scoremaster';
        $document = $excel->create($filename, function (LaravelExcelWriter $excel) use ($players) {
            $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players) {
                $sheet->appendRow([
                    'Division',
                    'Group GUID',
                    'Group',
                    'Group City',
                    'Group State',
                    'Player GUID',
                    'Last Name',
                    'First Name',
                    'Grade',
                ]);

                /** @var Player $player */
                foreach ($players as $player) {
                    /** @var Group $group */
                    $group = $player->groups->first();

                    $sheet->appendRow([
                        $group->program->abbreviation,
                        $group->guid,
                        $group->name,
                        $group->meetingAddress->city,
                        $group->meetingAddress->state,
                        $player->guid,
                        $player->last_name,
                        $player->first_name,
                        $player->seasons->first()->pivot->grade,
                    ]);
                }
            });
        });

        return $document->string('csv');
    }
}
