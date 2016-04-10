<?php

use BibleBowl\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Omnipay\Stripe\Message\PurchaseRequest;
use Omnipay\Stripe\Message\Response;
use BibleBowl\Receipt;
use BibleBowl\Seasons\SeasonRotator;
use Carbon\Carbon;

class RotatorTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function canRegisterPlayers()
    {
        $birthday = Mockery::mock(Carbon::class);
        $birthday->shouldReceive('isBirthday')->andReturn(true);
        Setting::shouldReceive('seasonEnd')->andReturn($birthday);
        Setting::shouldReceive('seasonStart')->andReturn(Carbon::now());

        Artisan::call(SeasonRotator::COMMAND);
    }
}