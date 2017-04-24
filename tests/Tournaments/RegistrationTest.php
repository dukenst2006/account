<?php

use App\Tournament;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function cantRegisterWhenRegistrationClosed()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->update([
            'registration_start'    => Carbon::now('America/New_York')->subDays(10)->format('m/d/Y'),
            'registration_end'      => Carbon::now('America/New_York')->subDays(1)->format('m/d/Y'),
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->see('Online registration for this tournament is now closed');
    }

    /** @test */
    public function suggestsOnsiteRegistration()
    {
        $tournament = Tournament::firstOrFail();
        $tournament->update([
            'registration_start'    => Carbon::now('America/New_York')->subDays(10)->format('m/d/Y'),
            'registration_end'      => Carbon::now('America/New_York')->subDays(1)->format('m/d/Y'),
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->see('Quizmaster, Adult and Family registrations will be accepted onsite.');
    }

    /** @test */
    public function showsNoticeForNumberOfRequiredQuizmasters()
    {
        $tournament = Tournament::firstOrFail();
        $settings = $tournament->settings;
        $settings->requireQuizmasters('group');
        $settings->setQuizmastersToRequireByGroup(2);
        $tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->see('Groups registering teams are required to register 2 quizmasters');
    }

    /** @test */
    public function showsNoticeForRequiringQuizmastersByTeamCount()
    {
        $tournament = Tournament::firstOrFail();
        $settings = $tournament->settings;
        $settings->requireQuizmasters('team_count');
        $settings->setQuizmastersToRequireByTeamCount(3);
        $settings->setTeamCountToRequireQuizmastersBy(4);
        $tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->see('Groups registering teams are required to register 3 quizmasters for every 4 teams');
    }
}
