<?php namespace BibleBowl\Competition;

use BibleBowl\Season;
use BibleBowl\Tournament;
use BibleBowl\User;
use DB;

class TournamentCreator
{
    /**
     * @param User    $owner
     * @param Season  $season
     * @param array   $attributes
     *
     * @return static
     */
    public function create(User $owner, Season $season, array $attributes)
    {
        $attributes['creator_id'] = $owner->id;
        $attributes['season_id'] = $season->id;

        DB::beginTransaction();

        $tournament = Tournament::create($attributes);

        DB::commit();

        return $tournament;
    }
}
