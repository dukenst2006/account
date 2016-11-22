<?php

namespace BibleBowl\Competition;

use BibleBowl\Competition\Tournaments\Settings;
use BibleBowl\Tournament;
use DB;

class TournamentUpdater
{
    public function update(Tournament $tournament, array $attributes, array $participantTypes)
    {
        DB::beginTransaction();

        /** @var Settings $settings */
        $settings = $tournament->settings;
        $settings->collectShirtSizes(array_pull($attributes, 'collect_shirt_sizes'));
        $settings->collectQuizmasterPreferences(array_pull($attributes, 'collect_quizmaster_preferences'));
        $settings->setMinimumPlayersPerTeam(array_pull($attributes, 'minimum_players_per_team'));
        $settings->setMaximumPlayersPerTeam(array_pull($attributes, 'maximum_players_per_team'));
        $attributes['settings'] = $settings;

        $tournament->update($attributes);

        // update or add a new fee
        foreach ($participantTypes as $typeId => $registration) {
            $participantFee = $tournament->participantFees()->where('participant_type_id', $typeId)->first();
            if (is_object($participantFee) && $participantFee->exists) {
                $participantFee->update([
                    'requires_registration' => isset($registration['requireRegistration']) ? (bool) $registration['requireRegistration'] : false,
                    'fee'                   => $registration['fee'],
                    'earlybird_fee'         => $registration['earlybird_fee'],
                    'onsite_fee'            => $registration['onsite_fee'],
                ]);
            } else {
                $tournament->participantFees()->create([
                    'participant_type_id'   => $typeId,
                    'requires_registration' => isset($registration['requireRegistration']) ? (bool) $registration['requireRegistration'] : false,
                    'fee'                   => is_numeric($registration['fee']) ? $registration['fee'] : null,
                    'earlybird_fee'         => is_numeric($registration['earlybird_fee']) ? $registration['earlybird_fee'] : null,
                    'onsite_fee'            => is_numeric($registration['onsite_fee']) ? $registration['onsite_fee'] : null,
                ]);
            }
        }

        DB::commit();

        return $tournament;
    }
}
