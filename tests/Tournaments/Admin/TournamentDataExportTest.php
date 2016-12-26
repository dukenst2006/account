<?php

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
}
