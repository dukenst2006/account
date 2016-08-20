<?php namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Season;
use DB;

class PlayerMetricsRepository
{
    public function playerStats(Season $season, Group $group = null)
    {
        $baseModel = $group;
        if ($group == null) {
            $baseModel = $season;
        }

        return [
            'byGender'  => $baseModel->players()->active($season)
                ->select('players.gender', DB::raw('count(players.id) as total'))
                ->groupBy('players.gender')
                ->get()->toArray(),
            'byGrade'   => $baseModel->players()->active($season)
                ->select('player_season.grade', DB::raw('count(players.id) as total'))
                ->groupBy('player_season.grade')
                ->get()->toArray(),
            'total'     => $baseModel->players()->active($season)->count()
        ];
    }

    public function bySchoolSegment(Season $season)
    {
        $playersByGrade = $season->players()->active($season)
            ->select('player_season.grade', DB::raw('count(players.id) as total'))
            ->groupBy('player_season.grade')
            ->orderBy('player_season.grade', 'ASC')
            ->get();

        $elementary = 0;
        $middle = 0;
        $high = 0;
        foreach ($playersByGrade as $attrs) {
            if ($attrs->grade < 6) {
                $elementary += $attrs->total;
            } elseif ($attrs->grade < 9) {
                $middle += $attrs->total;
            } else {
                $high += $attrs->total;
            }
        }
        return [
            'Elementary'    => $elementary,
            'Middle'        => $middle,
            'High'          => $high
        ];
    }
}
