<?php namespace BibleBowl\Competition;

use BibleBowl\Season;
use BibleBowl\Tournament;
use BibleBowl\User;
use DB;

class TournamentUpdater
{
    public function update(Tournament $tournament, array $attributes, array $participantTypes)
    {
        DB::beginTransaction();

        $tournament->update($attributes);

        // update or add a new fee
        foreach ($participantTypes as $typeId => $registration) {
            $participantFee = $tournament->participantFees()->where('participant_type_id', $typeId)->first();
            if (is_object($participantFee) && $participantFee->exists) {
                $participantFee->update([
                    'requires_registration' => isset($registration['requireRegistration']) ? !!$registration['requireRegistration'] : false,
                    'fee'                   => $registration['fee']
                ]);
            } else {
                $tournament->participantFees()->create([
                    'participant_type_id'   => $typeId,
                    'requires_registration' => isset($registration['requireRegistration']) ? !!$registration['requireRegistration'] : false,
                    'fee'                   => is_numeric($registration['fee']) ? $registration['fee'] : null
                ]);
            }
        }

        DB::commit();

        return $tournament;
    }
}
