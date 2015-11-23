<?php namespace BibleBowl\Reporting;

use BibleBowl\Program;
use BibleBowl\Season;
use DB;

class MetricsRepository
{
    public function historicalGroupSummaryByProgram()
    {
        return Season::orderBy('seasons.created_at', 'DESC')
            ->addSelect(
                'seasons.*',
                DB::raw('(SELECT COUNT(group_id) FROM player_season WHERE program_id = '.Program::BEGINNER.' AND season_id = seasons.id) as beginner_count'),
                DB::raw('(SELECT COUNT(group_id) FROM player_season WHERE program_id = '.Program::TEEN.' AND season_id = seasons.id) as teen_count')
            )
            ->limit(5)
            ->get();
    }

    public function historicalPlayerSummaryByProgram()
    {
        return Season::orderBy('seasons.created_at', 'DESC')
            ->addSelect(
                'seasons.*',
                DB::raw('(SELECT COUNT(player_id) FROM player_season WHERE program_id = '.Program::BEGINNER.' AND season_id = seasons.id AND inactive IS NULL) as beginner_count'),
                DB::raw('(SELECT COUNT(player_id) FROM player_season WHERE program_id = '.Program::BEGINNER.' AND season_id = seasons.id AND inactive IS NOT NULL) as beginner_quitters_count'),
                DB::raw('(SELECT COUNT(player_id) FROM player_season WHERE program_id = '.Program::TEEN.' AND season_id = seasons.id AND inactive IS NULL) as teen_count'),
                DB::raw('(SELECT COUNT(player_id) FROM player_season WHERE program_id = '.Program::TEEN.' AND season_id = seasons.id AND inactive IS NOT NULL) as teen_quitters_count')
            )
            ->limit(5)
            ->get();
    }
}