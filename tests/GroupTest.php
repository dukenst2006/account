<?php

use App\Group;
use App\ParticipantType;
use App\Player;
use App\Season;
use App\TeamSet;
use App\Tournament;

class GroupTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function hasPendingSeasonalRegistrations()
    {
        $season = Season::current()->firstOrFail();

        $this->assertEquals(2, Group::hasPendingRegistrationPayments($season)->count());
    }

    /** @test */
    public function hasNoPendingSeasonalRegistrationsWhenAllPlayersAreInactive()
    {
        $season = Season::current()->firstOrFail();
        DB::update('UPDATE player_season SET inactive = NOW()');
        $this->assertEquals(0, Group::hasPendingRegistrationPayments($season)->count());
    }

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

    /** @test */
    public function noFeeWhenFullDiscount()
    {
        $group = Group::findOrFail(2);

        Setting::setFirstYearDiscount(100);
        $season = Season::current()->firstOrFail();

        $playerIds = $group->players()->active($season)->get()->modelKeys();
        $this->assertEquals(0, $group->seasonalFee($playerIds));
    }

    /** @test */
    public function reducedFeeWhenDiscountSpecified()
    {
        $group = Group::findOrFail(2);

        Setting::setFirstYearDiscount(50);
        $season = Season::current()->firstOrFail();

        $playerIds = $group->players()->active($season)->get()->modelKeys();
        $this->assertEquals(35, $group->seasonalFee($playerIds));
    }

    /** @test */
    public function fullFeeWhenNoDiscount()
    {
        $group = Group::findOrFail(2);

        Setting::setFirstYearDiscount(0);
        $season = Season::current()->firstOrFail();

        $playerIds = $group->players()->active($season)->get()->modelKeys();
        $this->assertEquals(70, $group->seasonalFee($playerIds));
    }
}
