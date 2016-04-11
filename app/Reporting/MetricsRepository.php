<?php namespace BibleBowl\Reporting;

use BibleBowl\Group;
use BibleBowl\Program;
use BibleBowl\Season;
use DB;

/**
 * @phpcsignore
 */
class MetricsRepository
{

    public function groupCount($season)
    {
        return $season
            ->groups()
            ->active()
            ->count();
    }
    
    public function playerCount($season)
    {
        return $season
            ->players()
            ->active($season)
            ->count();
    }

    public function averageGroupSize(Season $season)
    {
        $average = DB::select('SELECT AVG(groupData.player_count) AS avg FROM (
          SELECT
                COUNT(player_season.id) AS player_count
            FROM groups
            INNER JOIN player_season ON (
                player_season.season_id = '.$season->id.' AND
                player_season.group_id = groups.id AND
                player_season.inactive IS NULL
            )
            WHERE groups.inactive IS NULL
        ) groupData');
        return (int)round($average[0]->avg);
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
