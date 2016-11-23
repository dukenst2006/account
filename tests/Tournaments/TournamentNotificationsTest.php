<?php

use BibleBowl\Competition\Tournaments\Groups\RemindEarlyBirdFeeEnding;
use BibleBowl\Competition\Tournaments\Groups\RemindRegistrationEnding;
use BibleBowl\ParticipantType;
use BibleBowl\TeamSet;
use BibleBowl\Tournament;
use Carbon\Carbon;

class TournamentNotificationsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsHeadCoach;
    use \Helpers\SimulatesTransactions;

    /** @var Tournament */
    protected $tournament;

    public function setUp()
    {
        parent::setUp();

        $this->tournament = Tournament::firstOrFail();
    }

    /** @test */
    public function remindsHeadCoachesOfExpiringEarlyBirdRegistration()
    {
        $this->tournament->update([
            'earlybird_ends' => Carbon::now()->addDays(7),
        ]);
        $this->tournament->participantFees()->update([
            'fee' => null,
        ]);
        $this->tournament->participantFees()->where('participant_type_id', ParticipantType::QUIZMASTER)->update([
            'fee' => 5,
        ]);

        $this->artisan(RemindEarlyBirdFeeEnding::COMMAND);
    }

    /** @test */
    public function remindsHeadCoachesOfEndingRegistration()
    {
        // associate a TeamSet with a tournament so we have a group to compare against
        $teamSet = TeamSet::firstOrFail();
        $teamSet->update([
            'tournament_id' => $this->tournament->id,
        ]);

        $this->tournament->update([
            'registration_end' => Carbon::now(),
        ]);
        Carbon::setTestNow(Carbon::now()->subDays(7));

        $this->artisan(RemindRegistrationEnding::COMMAND);
    }
}
