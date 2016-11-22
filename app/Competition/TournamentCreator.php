<?php

namespace BibleBowl\Competition;

use BibleBowl\Competition\Tournaments\Settings;
use BibleBowl\Season;
use BibleBowl\Tournament;
use BibleBowl\User;
use DB;

class TournamentCreator
{
    /**
     * @param User   $owner
     * @param Season $season
     * @param array  $attributes
     *
     * @return static
     */
    public function create(User $owner, Season $season, array $attributes, array $eventTypes, array $participantTypes)
    {
        $attributes['creator_id'] = $owner->id;
        $attributes['season_id'] = $season->id;

        // don't prefix with season if the name already contains the year (20xx)
        if (str_contains($attributes['name'], '20') == false) {
            $attributes['slug'] = $season->name.' '.$attributes['name'];
        } else {
            $attributes['slug'] = $attributes['name'];
        }

        /** @var Settings $settings */
        $settings = new Settings();
        $settings->collectShirtSizes(array_pull($attributes, 'collect_shirt_sizes'));
        $settings->collectQuizmasterPreferences(array_pull($attributes, 'collect_quizmaster_preferences'));
        $settings->setMinimumPlayersPerTeam(array_pull($attributes, 'minimum_players_per_team'));
        $settings->setMaximumPlayersPerTeam(array_pull($attributes, 'maximum_players_per_team'));
        $attributes['settings'] = $settings;

        DB::beginTransaction();

        $tournament = Tournament::create($attributes);

        // add fees
        foreach ($participantTypes as $typeId => $registration) {
            $tournament->participantFees()->create([
                'participant_type_id'   => $typeId,
                'requires_registration' => isset($registration['requireRegistration']) ? (bool) $registration['requireRegistration'] : false,
                'fee'                   => is_numeric($registration['fee']) ? $registration['fee'] : null,
                'earlybird_fee'         => is_numeric($registration['earlybird_fee']) ? $registration['earlybird_fee'] : null,
                'onsite_fee'            => is_numeric($registration['onsite_fee']) ? $registration['onsite_fee'] : null,
            ]);
        }

        // add events
        foreach ($eventTypes as $eventTypeId) {
            $tournament->events()->create([
                'event_type_id' => $eventTypeId,
            ]);
        }

        DB::commit();

        return $tournament;
    }
}
