<?php

use BibleBowl\Group;
use BibleBowl\Receipt;
use Helpers\ActingAsHeadCoach;
use Helpers\SimulatesTransactions;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeasonalRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use ActingAsHeadCoach;
    use SimulatesTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /**
     * @test
     */
    public function canRegisterPlayers()
    {
        $transactionId = $this->simulateTransaction();

        $startingCount = $this->group()->players()->active($this->season())->whereRaw('player_season.paid IS NULL')->count();
        $this->assertGreaterThan(0, $startingCount);
        $this
            ->visit('/players/pay')
            ->press('Continue')
            ->seePageIs('/cart')
            ->press('Submit')
            ->see('Payment has been received!');
        $this->assertEquals($startingCount, $this->group()->players()->whereRaw('player_season.paid IS NOT NULL')->count());

        // verify the transaction was recorded
        $receipt = Receipt::where('payment_reference_number', $transactionId)->first();
        $this->assertTrue($receipt->exists);

        $this->assertGreaterThan(0, $receipt->items->count());
    }

    /**
     * @test
     */
    public function receivesNotificationsForPastDueRegistrationFees()
    {
        Mail::shouldReceive('queue')->once();
        Artisan::call(\BibleBowl\Seasons\RemindGroupsOfPendingRegistrationPayments::COMMAND);
    }
}
