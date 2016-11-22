<?php

use BibleBowl\Competition\Tournaments\Settings;
use BibleBowl\Group;
use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use Helpers\ActingAsGuardian;
use Helpers\SimulatesTransactions;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuizmasterRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use ActingAsGuardian;
    use SimulatesTransactions;

    /** @var Tournament */
    protected $tournament;

    public function setUp()
    {
        parent::setUp();

        $this->tournament = Tournament::firstOrFail();
    }

    /**
     * @test
     */
    public function canRegisterWithoutGroupAndWithFees()
    {
        $this->setupAsGuardian();
        $this->simulateTransaction();

        $shirtSize = 'XL';
        $gamesQuizzedThisSeason = 'Fewer than 30';
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/registration/quizmaster')
            ->select($gamesQuizzedThisSeason, 'games_quizzed_this_season')
            ->select($shirtSize, 'shirt_size')
            ->press('Continue')
            ->seePageIs('/cart')
            ->see('Quizmaster Tournament Registration')
            ->press('Submit')
            ->see('Your quizmaster registration is complete');

        $quizmaster = TournamentQuizmaster::orderBy('id', 'desc')->first();
        $this->assertEquals($shirtSize, $quizmaster->shirt_size);

        // defaults to no group selected
        $this->assertNull($quizmaster->group_id);

        // quizzing preferences saved
        $this->assertEquals($gamesQuizzedThisSeason, $quizmaster->quizzing_preferences->gamesQuizzedThisSeason());

        // we use the receipt_id to determine if payment has been made
        $this->assertGreaterThan(0, $quizmaster->receipt_id);
    }

    /**
     * @test
     */
    public function canRegisterWithoutShirtSize()
    {
        $this->setupAsGuardian();

        $gamesQuizzedThisSeason = 'Fewer than 30';

        /** @var Settings $settings */
        $settings = $this->tournament->settings;
        $settings->collectShirtSizes(false);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/registration/quizmaster')
            ->select($gamesQuizzedThisSeason, 'games_quizzed_this_season')
            ->dontSee('T-Shirt Size')
            ->press('Continue')
            ->seePageIs('/cart')
            ->see('Quizmaster Tournament Registration');

        $quizmaster = TournamentQuizmaster::orderBy('id', 'desc')->first();
        $this->assertNull($quizmaster->shirt_size);
    }

    /**
     * @test
     */
    public function cantRegisterMoreThanOnce()
    {
        $this->setupAsGuardian();

        $this
            ->visit('/tournaments/'.$this->tournament->slug);

        TournamentQuizmaster::create([
            'tournament_id' => $this->tournament->id,
            'user_id'       => $this->guardian()->id,
        ]);

        $this
            ->click('#register-quizmaster')
            ->see("You've already registered for this tournament");
    }

    /**
     * @test
     */
    public function canRegisterWithoutQuizzingPreferences()
    {
        $this->setupAsGuardian();

        /** @var Settings $settings */
        $settings = $this->tournament->settings;
        $settings->collectQuizmasterPreferences(false);
        $this->tournament->update([
            'settings' => $settings,
        ]);

        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/registration/quizmaster')
            ->dontSee('How many games have you quizzed this season?')
            ->press('Continue')
            ->seePageIs('/cart')
            ->see('Quizmaster Tournament Registration');
    }

    /**
     * @test
     */
    public function canRegisterWithGroupAndWithoutFees()
    {
        $this->setupAsGuardian();
        $this->simulateTransaction();

        $gamesQuizzedThisSeason = 'Fewer than 30';

        // Remove fees for quizmasters
        $this->tournament->participantFees()
            ->where('participant_type_id', ParticipantType::QUIZMASTER)
            ->update([
                'earlybird_fee' => 0,
                'fee'           => 0,
        ]);

        $group = Group::byProgram($this->tournament->program_id)->first();
        $this
            ->visit('/tournaments/'.$this->tournament->slug.'/registration/quizmaster')
            ->select($group->id, 'group_id')
            ->select($gamesQuizzedThisSeason, 'games_quizzed_this_season')
            ->press('Submit')
            ->see('Your quizmaster registration is complete');

        $quizmaster = TournamentQuizmaster::orderBy('id', 'desc')->first();

        $this->assertEquals($group->id, $quizmaster->group_id);

        // quizzing preferences saved
        $this->assertEquals($gamesQuizzedThisSeason, $quizmaster->quizzing_preferences->gamesQuizzedThisSeason());

        // no payment was made, so we shouldn't have a receipt
        $this->assertNull($quizmaster->receipt_id);
    }

    /**
     * @test
     */
    public function cantRegisterAsGuest()
    {
        $this
            ->visit('/tournaments/'.$this->tournament->slug)
            ->click('#register-quizmaster')

            // if we don't go anywhere, the tooltip was hopefully shown
            ->seePageIs('/tournaments/'.$this->tournament->slug);
    }
}
