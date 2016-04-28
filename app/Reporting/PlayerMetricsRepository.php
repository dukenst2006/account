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
            'byGender' => $baseModel->players()->active($season)
                ->select('players.gender', DB::raw('count(players.id) as total'))
                ->groupBy('players.gender')
                ->get()->toArray(),
            'byGrade' => $baseModel->players()->active($season)
                ->select('player_season.grade', DB::raw('count(players.id) as total'))
                ->groupBy('player_season.grade')
                ->get()->toArray()
        ];
    }
}
