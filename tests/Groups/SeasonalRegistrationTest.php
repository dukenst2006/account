<?php

use BibleBowl\Group;
use BibleBowl\Receipt;
use Helpers\ActingAsHeadCoach;
use Helpers\SimulatesTransactions;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Helpers\ActingAsDirector;
use BibleBowl\User;

class SeasonalRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use ActingAsHeadCoach {
        ActingAsHeadCoach::season as headCoachSeason;
    }
    use ActingAsDirector {
        ActingAsDirector::season insteadof ActingAsHeadCoach;
        ActingAsDirector::season as directorSeason;
    }
    use SimulatesTransactions;

    /** @test */
    public function headCoachCanRegisterPlayers()
    {
        $this->setupAsHeadCoach();

        $transactionId = $this->simulateTransaction();

        $startingCount = $this->group()->players()->active($this->headCoachSeason())->whereRaw('player_season.paid IS NULL')->count();
        $this->assertGreaterThan(0, $startingCount);
        $this
            ->visit('/players/pay')
            ->press('Continue')
            ->seePageIs('/cart')

            // doesn't see admin only option to supply a check number
            ->dontSee('Payment Reference Number')

            ->press('Submit')
            ->see('Payment has been received!');

        $this->assertEquals($startingCount, $this->group()->players()->whereRaw('player_season.paid IS NOT NULL')->count());

        // verify the transaction was recorded
        $receipt = Receipt::where('payment_reference_number', $transactionId)->first();
        $this->assertTrue($receipt->exists);

        $this->assertGreaterThan(0, $receipt->items->count());
    }

    /** @test */
    public function adminCanAvoidCreditCardPayment()
    {
        $this->setupAsDirector();

        $this->loginAdminAs(DatabaseSeeder::HEAD_COACH_EMAIL);

        $paymentReferenceNumber = '4j3ncd';
        $startingCount = Session::group()->players()->active($this->directorSeason())->whereRaw('player_season.paid IS NULL')->count();
        $this->assertGreaterThan(0, $startingCount);
        $this
            ->visit('/players/pay')
            ->press('Continue')
            ->seePageIs('/cart')
            ->type($paymentReferenceNumber, 'payment_reference_number')
            ->press('Submit')
            ->see('Payment has been received!');
        $this->assertEquals($startingCount, Session::group()->players()->whereRaw('player_season.paid IS NOT NULL')->count());

        // verify the transaction was recorded
        $receipt = Receipt::where('payment_reference_number', $paymentReferenceNumber)->first();
        $this->assertTrue($receipt->exists);

        $this->assertGreaterThan(0, $receipt->items->count());
    }

    /** @test */
    public function receivesNotificationsForPastDueRegistrationFees()
    {
        Artisan::call(\BibleBowl\Seasons\RemindGroupsOfPendingRegistrationPayments::COMMAND);
    }
}
