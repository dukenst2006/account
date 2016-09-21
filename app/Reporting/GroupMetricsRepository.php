<?php

namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Season;
use DB;
use Illuminate\Database\Eloquent\Builder;

class GroupMetricsRepository
{
    public function groupStats(Season $season)
    {
        return [
            'byProgram'  => Group::whereHas('players', function (Builder $q) use ($season) {
                $q->where('player_season.season_id', $season->id)
                        ->whereNull('player_season.inactive');
            })
                ->with('program')
                ->select('groups.program_id', DB::raw('count(groups.id) as total'))
                ->groupBy('groups.program_id')
                ->get(),
            'byType'  => Group::whereHas('players', function (Builder $q) use ($season) {
                $q->where('player_season.season_id', $season->id)
                        ->whereNull('player_season.inactive');
            })
                ->with('type')
                ->select('groups.group_type_id', DB::raw('count(groups.id) as total'))
                ->groupBy('groups.group_type_id')
                ->get(),
        ];
    }
}
