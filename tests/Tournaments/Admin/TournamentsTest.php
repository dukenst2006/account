<?php

use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use Carbon\Carbon;

class TournamentsTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function canCreateTournament()
    {
        $name = 'My Tournament '.time();
        $soon = Carbon::now()->addMonths(1)->format('m/d/Y');

        $this
            ->visit('/admin/tournaments/create')
            ->type($name, 'name')
            ->press('Save')
            ->see('A registration start date is required')

            ->type($soon, 'start')
            ->type($soon, 'end')
            ->type($soon, 'registration_start')
            ->type($soon, 'registration_end')
            ->type(24, 'max_teams')
            ->type(33.30, 'participantTypes['.ParticipantType::PLAYER.'][earlybird_fee]')
            ->type(25.50, 'participantTypes['.ParticipantType::PLAYER.'][fee]')
            ->type(45.30, 'participantTypes['.ParticipantType::PLAYER.'][onsite_fee]')
            ->select('team_count', 'require_quizmasters_per')
            ->select('2', 'quizmasters_per_team_count')
            ->select('3', 'quizmasters_team_count')
            ->select('4', 'quizmasters_per_group')
            ->check('participantTypes['.ParticipantType::QUIZMASTER.'][requireRegistration]')
            ->press('Save')
            ->seePageIs('/admin/tournaments')
            ->see($name);

        $tournament = Tournament::orderBy('id', 'desc')->firstOrFail();
        $playerFees = $tournament->participantFees()->where('participant_type_id', ParticipantType::PLAYER)->first();
        $this->assertEquals('33.30', $playerFees->earlybird_fee);
        $this->assertEquals('25.50', $playerFees->fee);
        $this->assertEquals('45.30', $playerFees->onsite_fee);

        // verify all options are saved, even though we're only actually requiring QM's for groups
        $this->assertTrue($tournament->settings->shouldRequireQuizmasters());

        $this->assertFalse($tournament->settings->shouldRequireQuizmastersByGroup());
        $this->assertEquals(4, $tournament->settings->quizmastersToRequireByGroup());

        $this->assertTrue($tournament->settings->shouldRequireQuizmastersByTeamCount());
        $this->assertEquals(2, $tournament->settings->quizmastersToRequireByTeamCount());
        $this->assertEquals(3, $tournament->settings->teamCountToRequireQuizmastersBy());
    }

    /** @test */
    public function quizmasterRegistrationIsRequiredWhenRequiringQuizmastersOfGroups()
    {
        $name = 'My Tournament '.time();

        $this
            ->visit('/admin/tournaments/create')
            ->type($name, 'name')
            ->select('team_count', 'require_quizmasters_per')
            ->select('2', 'quizmasters_per_team_count')
            ->select('3', 'quizmasters_team_count')
            ->select('4', 'quizmasters_per_group')
            ->press('Save')
            ->see('Quizmaster registration must be enabled in order to require quizmasters by groups');
    }

    /** @test */
    public function canEditTournament()
    {
        $tournament = Tournament::findOrFail(1);
        $newName = $tournament->name.time();
        $this
            ->visit('/admin/tournaments/1/edit')
            ->type($newName, 'name')
            ->type(33.10, 'participantTypes['.ParticipantType::PLAYER.'][earlybird_fee]')
            ->type(25.10, 'participantTypes['.ParticipantType::PLAYER.'][fee]')
            ->type(45.10, 'participantTypes['.ParticipantType::PLAYER.'][onsite_fee]')
            ->press('Save')
            ->see($tournament->name);

        $tournament = Tournament::where('id', 1)->firstOrFail();
        $playerFees = $tournament->participantFees()->where('participant_type_id', ParticipantType::PLAYER)->first();
        $this->assertEquals('33.10', $playerFees->earlybird_fee);
        $this->assertEquals('25.10', $playerFees->fee);
        $this->assertEquals('45.10', $playerFees->onsite_fee);
    }

    /** @test */
    public function doesntShowRegistrationFees()
    {
        $tournament = Tournament::findOrFail(1);
        $tournament->participantFees()->update([
            'fee'           => null,
            'onsite_fee'    => null,
            'earlybird_fee' => null,
        ]);

        $this
            ->visit('/tournaments/'.$tournament->slug)
            ->dontSee('RegistrationFees');
    }

    /** @test */
    public function viewUser()
    {
        $tournament = Tournament::first();

        $this
            ->visit('/admin/tournaments/'.$tournament->id)
            ->see($tournament->name);
    }
}
