<?php namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Program;
use BibleBowl\Season;
use DB;

/**
 * @phpcsignore
 */
class MetricsRepository
{

    public function groupCount()
    {
        return Group::active()->count();
    }
    
    public function playerCount($season)
    {
        return $season
            ->players()
            ->active($season)
            ->count();
    }

    public function historicalGroupSummaryByProgram()
    {
        return Season::orderBy('seasons.created_at', 'DESC')
            ->addSelect(
                'seasons.*',
                DB::raw('(SELECT COUNT(group_id) FROM player_season INNER JOIN `groups` ON (`groups`.id = player_season.group_id AND `groups`.program_id = '.Program::BEGINNER.') WHERE season_id = seasons.id) as beginner_count'),
                DB::raw('(SELECT COUNT(group_id) FROM player_season INNER JOIN `groups` ON (`groups`.id = player_season.group_id AND `groups`.program_id = '.Program::TEEN.') WHERE season_id = seasons.id) as teen_count')
            )
            ->limit(5)
            ->get();
    }

    public function historicalPlayerSummaryByProgram()
    {
        return Season::orderBy('seasons.created_at', 'DESC')
            ->addSelect(
                'seasons.*',
                DB::raw('(SELECT COUNT(player_id) FROM player_season INNER JOIN `groups` ON (`groups`.id = player_season.group_id AND `groups`.program_id = '.Program::BEGINNER.') WHERE season_id = seasons.id AND player_season.inactive IS NULL) as beginner_count'),
                DB::raw('(SELECT COUNT(player_id) FROM player_season INNER JOIN `groups` ON (`groups`.id = player_season.group_id AND `groups`.program_id = '.Program::BEGINNER.') WHERE season_id = seasons.id AND player_season.inactive IS NOT NULL) as beginner_quitters_count'),
                DB::raw('(SELECT COUNT(player_id) FROM player_season INNER JOIN `groups` ON (`groups`.id = player_season.group_id AND `groups`.program_id = '.Program::TEEN.') WHERE season_id = seasons.id AND player_season.inactive IS NULL) as teen_count'),
                DB::raw('(SELECT COUNT(player_id) FROM player_season INNER JOIN `groups` ON (`groups`.id = player_season.group_id AND `groups`.program_id = '.Program::TEEN.') WHERE season_id = seasons.id AND player_season.inactive IS NOT NULL) as teen_quitters_count')
            )
            ->limit(5)
            ->get();
    }
}
