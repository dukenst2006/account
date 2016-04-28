<?php

use BibleBowl\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Omnipay\Stripe\Message\PurchaseRequest;
use Omnipay\Stripe\Message\Response;
use BibleBowl\Receipt;

class SeasonalRegistrationTest extends TestCase
{

    use DatabaseTransactions;
    use \Helpers\ActingAsHeadCoach;

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
        $transactionId = uniqid();

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('isSuccessful')->andReturn(true);
        $response->shouldReceive('getTransactionReference')->andReturn($transactionId);
        $purchaseRequest = Mockery::mock(PurchaseRequest::class);
        $purchaseRequest->shouldReceive('send')->andReturn($response);
        Omnipay::shouldReceive('purchase')->andReturn($purchaseRequest);

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
