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
    public function create(User $owner, Season $season, array $attributes, array $eventTypes)
    {
        $attributes['creator_id'] = $owner->id;
        $attributes['season_id'] = $season->id;

        // don't prefix with season if the name already contains the year (20xx)
        if (str_contains($attributes['name'], '20')) {
            $attributes['slug'] = $season->name.' '.$attributes['name'];
        }

        DB::beginTransaction();

        $tournament = Tournament::create($attributes);
        foreach ($eventTypes as $eventTypeId) {
            $tournament->events()->create([
                'event_type_id' => $eventTypeId
            ]);
        }

        DB::commit();

        return $tournament;
    }
}
