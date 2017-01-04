<?php

use BibleBowl\TeamSet;
use BibleBowl\Tournament;

class TournamentDataExportTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function canExportQuizmasters()
    {
        $tournament = Tournament::first();
        $quizmaster = $tournament->eligibleQuizmasters()->first();

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/participants/quizmasters/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($quizmaster->first_name, $csvContents);
        $this->assertContains($quizmaster->last_name, $csvContents);
        $this->assertContains('Mount Pleasant', $csvContents);
        $this->assertContains('T-shirt Size', $csvContents);
        $this->assertContains('Times Quizzed At This Tournament', $csvContents);
    }

    /** @test */
    public function canExportQuizmastersWithHiddenTshirtSize()
    {
        $tournament = Tournament::first();
        $quizmaster = $tournament->eligibleQuizmasters()->first();
        $settings = $tournament->settings;
        $settings->collectShirtSizes(false);
        $tournament->settings = $settings;
        $tournament->save();

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/participants/quizmasters/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($quizmaster->first_name, $csvContents);
        $this->assertContains($quizmaster->last_name, $csvContents);
        $this->assertContains('Mount Pleasant', $csvContents);
        $this->assertNotContains('T-shirt Size', $csvContents);
    }

    /** @test */
    public function canExportQuizmastersWithHiddenQuizmasterPreferences()
    {
        $tournament = Tournament::first();
        $quizmaster = $tournament->eligibleQuizmasters()->first();
        $settings = $tournament->settings;
        $settings->collectQuizmasterPreferences(false);
        $tournament->settings = $settings;
        $tournament->save();

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/participants/quizmasters/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($quizmaster->first_name, $csvContents);
        $this->assertContains($quizmaster->last_name, $csvContents);
        $this->assertContains('Mount Pleasant', $csvContents);
        $this->assertNotContains('Times Quizzed At This Tournament', $csvContents);
    }

    /** @test */
    public function canExportTeams()
    {
        $tournament = Tournament::first();

        // remove fees
        $tournament->participantFees()->update([
            'fee'           => null,
            'onsite_fee'    => null,
            'earlybird_fee' => null,
        ]);

        // remove number of players on team restriction
        $settings = $tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);
        $tournament->update([
            'settings' => $settings,
        ]);

        // link teams to tournament
        $teamSet = TeamSet::firstOrFail();
        $teamSet->update([
            'tournament_id' => $tournament->id,
        ]);

        $player = $tournament->eligiblePlayers()->first();

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/participants/teams/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($player->first_name, $csvContents);
        $this->assertContains($player->last_name, $csvContents);
        $this->assertContains('Mount Pleasant', $csvContents);
        $this->assertContains('Team 3', $csvContents);
    }

    /** @test */
    public function canExportPlayers()
    {
        $tournament = Tournament::first();

        // remove fees
        $tournament->participantFees()->update([
            'fee'           => null,
            'onsite_fee'    => null,
            'earlybird_fee' => null,
        ]);

        // remove number of players on team restriction
        $settings = $tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);
        $tournament->update([
            'settings' => $settings,
        ]);

        // link teams to tournament
        $teamSet = TeamSet::firstOrFail();
        $teamSet->update([
            'tournament_id' => $tournament->id,
        ]);

        $player = $tournament->eligiblePlayers()->first();

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/participants/players/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($player->first_name, $csvContents);
        $this->assertContains($player->last_name, $csvContents);
        $this->assertContains('Mount Pleasant', $csvContents);
    }

    /** @test */
    public function canExportShirtSizes()
    {
        $tournament = Tournament::first();

        // remove fees
        $tournament->participantFees()->update([
            'fee'           => null,
            'onsite_fee'    => null,
            'earlybird_fee' => null,
        ]);

        // remove number of players on team restriction
        $settings = $tournament->settings;
        $settings->setMinimumPlayersPerTeam(0);
        $settings->setMaximumPlayersPerTeam(10);
        $tournament->update([
            'settings' => $settings,
        ]);

        // link teams to tournament
        $teamSet = TeamSet::firstOrFail();
        $teamSet->update([
            'tournament_id' => $tournament->id,
        ]);

        ob_start();
        $this
            ->visit('/admin/tournaments/'.$tournament->id.'/participants/tshirts/export/csv')
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains('"Quizmasters","0","0","0","0","1","0","1","0"', $csvContents);
        $this->assertContains('"Players","2","0","0","1","1","0","0","0"', $csvContents);
        $this->assertContains('"Adults/Families","1","1","0","0","0","2","1","0"', $csvContents);
    }
}
