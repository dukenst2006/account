<?php

namespace App\Competition;

use App\Competition\Tournaments\Settings;
use App\Tournament;
use DB;

class TournamentUpdater
{
    public function update(Tournament $tournament, array $attributes, array $participantTypes)
    {
        DB::beginTransaction();

        /** @var Settings $settings */
        $settings = $tournament->settings;
        $settings->collectShirtSizes(array_pull($attributes, 'collect_shirt_sizes', false));
        $settings->collectQuizmasterPreferences(array_pull($attributes, 'collect_quizmaster_preferences', false));
        $settings->setAllowGuestPlayers(array_pull($attributes, 'allow_guest_players', false));
        $settings->setMinimumPlayersPerTeam(array_pull($attributes, 'minimum_players_per_team'));
        $settings->setMaximumPlayersPerTeam(array_pull($attributes, 'maximum_players_per_team'));
        $settings->requireQuizmasters(array_pull($attributes, 'require_quizmasters_per'));
        $settings->setQuizmastersToRequireByGroup(array_pull($attributes, 'quizmasters_per_group'));
        $settings->setQuizmastersToRequireByTeamCount(array_pull($attributes, 'quizmasters_per_team_count'));
        $settings->setTeamCountToRequireQuizmastersBy(array_pull($attributes, 'quizmasters_team_count'));
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
