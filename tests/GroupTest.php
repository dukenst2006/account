<?php

use BibleBowl\Group;
use BibleBowl\ParticipantType;
use BibleBowl\Player;
use BibleBowl\TeamSet;
use BibleBowl\Tournament;

class GroupTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function noPendingFeesWhenThereAreNoFees()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->participantFees()->update([
            'fee' => null,
        ]);

        $this->assertEquals(0, Group::hasPendingTournamentRegistrationFees($tournament)->count());
    }

    /** @test */
    public function recognizesPendingTournamentRegistrationQuizmasterFees()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->participantFees()->update([
            'fee' => null,
        ]);
        $tournament->participantFees()->where('participant_type_id', ParticipantType::QUIZMASTER)->update([
            'fee' => 5,
        ]);

        $this->assertEquals(1, Group::hasPendingTournamentRegistrationFees($tournament)->count());
    }

    /** @test */
    public function recognizesPendingTournamentRegistrationAdultFees()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->participantFees()->update([
            'fee' => null,
        ]);
        $tournament->participantFees()->where('participant_type_id', ParticipantType::ADULT)->update([
            'fee' => 5,
        ]);

        $this->assertEquals(1, Group::hasPendingTournamentRegistrationFees($tournament)->count());
    }

    /** @test */
    public function recognizesPendingTournamentRegistrationFamilyFees()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->participantFees()->update([
            'fee' => null,
        ]);
        $tournament->participantFees()->where('participant_type_id', ParticipantType::FAMILY)->update([
            'fee' => 5,
        ]);

        $this->assertEquals(1, Group::hasPendingTournamentRegistrationFees($tournament)->count());
    }

    /** @test */
    public function recognizesPendingTournamentRegistrationTeamFees()
    {
        $tournament = Tournament::firstOrFail();
        TeamSet::where('id', 1)->update([
            'tournament_id' => $tournament->id,
        ]);
        $tournament->participantFees()->update([
            'fee' => null,
        ]);
        $tournament->participantFees()->where('participant_type_id', ParticipantType::TEAM)->update([
            'fee' => 5,
        ]);

        $this->assertEquals(1, Group::hasPendingTournamentRegistrationFees($tournament)->count());
    }

    /** @test */
    public function recognizesPendingTournamentRegistrationPlayerFees()
    {
        $tournament = Tournament::firstOrFail();
        TeamSet::where('id', 1)->update([
            'tournament_id' => $tournament->id,
        ]);
        $tournament->participantFees()->update([
            'fee' => null,
        ]);
        $tournament->participantFees()->where('participant_type_id', ParticipantType::PLAYER)->update([
            'fee' => 5,
        ]);

        $this->assertEquals(1, Group::hasPendingTournamentRegistrationFees($tournament)->count());
    }
}
