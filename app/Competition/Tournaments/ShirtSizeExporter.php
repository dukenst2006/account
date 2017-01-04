<?php

namespace BibleBowl\Competition\Tournaments;

use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use DB;
use BibleBowl\Player;
use BibleBowl\Presentation\Describer;
use Html;
use Illuminate\Database\Query\JoinClause;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\CellWriter;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class ShirtSizeExporter
{

    /** Excel */
    protected $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function export(Tournament $tournament) : LaravelExcelWriter
    {
        $excel = $this->excel;

        return $excel->create($tournament->slug.'_tshirt-sizes', function (LaravelExcelWriter $excel) use ($tournament) {
            $excel->sheet('T-shirt Sizes', function (LaravelExcelWorksheet $sheet) use ($tournament) {

                // build headers as "    |   YS    |    YM   | etc... "
                $headers = [''];
                foreach (Describer::SHIRT_SIZES as $shirtSize => $description) {
                    $headers[] = $shirtSize;
                }
                $sheet->appendRow($headers);

                $sheet->row(1, function (CellWriter $row) {
                    $row->setFontWeight('bold');
                });

                if($tournament->registrationIsEnabled(ParticipantType::QUIZMASTER)) {
                    $this->mapSizesToRow($sheet, $tournament, 'Quizmasters', $this->quizmasterSizes($tournament));
                }

                if($tournament->registrationIsEnabled(ParticipantType::PLAYER)) {
                    $this->mapSizesToRow($sheet, $tournament, 'Players', $this->playerSizes($tournament));
                }

                if($tournament->registrationIsEnabled(ParticipantType::ADULT) || $tournament->registrationIsEnabled(ParticipantType::FAMILY)) {
                    $this->mapSizesToRow($sheet, $tournament, 'Adults/Families', $this->spectatorSizes($tournament));
                }
            });
        });
    }

    protected function mapSizesToRow(LaravelExcelWorksheet $sheet, Tournament $tournament, string $description, array $data)
    {
        $row = [$description];
        foreach (Describer::SHIRT_SIZES as $shirtSize => $description) {
            if (isset($data[$shirtSize])) {
                $row[$shirtSize] = $data[$shirtSize];
            } else {
                $row[$shirtSize] = 0;
            }
        }
        $sheet->appendRow($row);
    }

    private function quizmasterSizes(Tournament $tournament) : array
    {
        $quizmasterShirts = $tournament->eligibleQuizmasters()
            ->select(DB::raw('COUNT(id) AS shirt_count'), 'shirt_size')
            ->groupBy('shirt_size')
            ->get();

        $sizes = [];
        foreach ($quizmasterShirts as $quizmasterSizes) {
            $sizes[$quizmasterSizes->shirt_size] = $quizmasterSizes->shirt_count;
        }

        return $sizes;
    }

    private function playerSizes(Tournament $tournament) : array
    {
        $playerShirts = $tournament->eligiblePlayers()
            ->join('player_season', function (JoinClause $join) use ($tournament) {
                $join->on('player_season.player_id', '=', 'players.id')
                    ->on('player_season.season_id', '=', DB::raw($tournament->season_id));
            })
            ->select(DB::raw('COUNT(players.id) AS shirt_count'), 'shirt_size')
            ->groupBy('shirt_size')
            ->get();

        $sizes = [];
        foreach ($playerShirts as $playerSizes) {
            $sizes[$playerSizes->shirt_size] = $playerSizes->shirt_count;
        }

        return $sizes;
    }

    private function spectatorSizes(Tournament $tournament) : array
    {
        $sizes = [];

        $spectatorShirts = $tournament->eligibleSpectators()
            ->select(DB::raw('COUNT(tournament_spectators.id) AS shirt_count'), 'shirt_size')
            ->groupBy('shirt_size')
            ->get();
        foreach ($spectatorShirts as $adultSizes) {
            $sizes[$adultSizes->shirt_size] = $adultSizes->shirt_count;
        }

        $spouseShirts = $tournament->eligibleSpectators()
            ->select(DB::raw('COUNT(tournament_spectators.id) AS shirt_count'), 'spouse_shirt_size')
            ->whereNotNull('spouse_shirt_size')
            ->groupBy('spouse_shirt_size')
            ->get();
        foreach ($spouseShirts as $spouseSizes) {
            $sizes[$spouseSizes->shirt_size] = isset($sizes[$spouseSizes->shirt_size]) ? $sizes[$spouseSizes->shirt_size] + $spouseSizes->shirt_count : $spouseSizes->shirt_count;
        }

        $minorShirts = $tournament->eligibleMinors()
            ->select(DB::raw('COUNT(tournament_spectator_minors.id) AS shirt_count'), 'tournament_spectator_minors.shirt_size')
            ->groupBy('tournament_spectator_minors.shirt_size')
            ->get();
        foreach ($minorShirts as $minorSizes) {
            $sizes[$minorSizes->shirt_size] = isset($sizes[$minorSizes->shirt_size]) ? $sizes[$minorSizes->shirt_size] + $minorSizes->shirt_count : $minorSizes->shirt_count;
        }

        return $sizes;
    }
}