<?php namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Season;
use DB;

class PlayerMetricsRepository
{
    public function playerStats(Season $season, Group $group)
    {
        return [
            'byGender' => $group->players()->active($season)
                ->select('players.gender', DB::raw('count(players.id) as total'))
                ->groupBy('players.gender')
                ->get()->toArray(),
            'byGrade' => $group->players()->active($season)
                ->select('player_season.grade', DB::raw('count(players.id) as total'))
                ->groupBy('player_season.grade')
                ->get()->toArray()
        ];
    }
}