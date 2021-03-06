<?php

namespace App\Reporting;

use App\Player;
use Closure;
use Html;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class PlayerExporter
{
    /** Excel */
    protected $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function export(string $filename, Collection $players, ?Closure $headerModifier = null, ?Closure $playerModifier = null) : LaravelExcelWriter
    {
        $excel = $this->excel;

        return $excel->create($filename, function (LaravelExcelWriter $excel) use ($players, $headerModifier, $playerModifier) {
            $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players, $headerModifier, $playerModifier) {
                $headers = [
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
                ];
                if ($headerModifier != null) {
                    $headers = $headerModifier($headers);
                }
                $sheet->appendRow($headers);

                $sheet->row(1, function (CellWriter $row) {
                    $row->setFontWeight('bold');
                });

                /** @var Player $player */
                foreach ($players as $player) {
                    $playerData = [
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
                    ];
                    if ($playerModifier != null) {
                        $playerData = $playerModifier($player, $playerData);
                    }
                    $sheet->appendRow($playerData);
                }
            });
        });
    }
}
