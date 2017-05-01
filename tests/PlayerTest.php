<?php

namespace App;

use DB;
use Setting;
use TestCase;

class PlayerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function providesPlayersPendingRegistrationPayment()
    {
        Setting::shouldReceive('firstYearDiscount')->once()->andReturn(75);

        $season = Season::findOrFail(2);

        $this->assertEquals(4, $season->players()->pendingRegistrationPayment($season)->count());
    }

    /** @test */
    public function excludesFirstYearPlayersFromPendingRegistrationPayments()
    {
        Setting::shouldReceive('firstYearDiscount')->once()->andReturn(100);

        Season::findOrFail(1)->players()->attach(5, [
            'grade'         => '11',
            'shirt_size'    => 'M',
            'group_id'      => 1,
        ]);

        $season = Season::findOrFail(2);
        $this->assertEquals(1, $season->players()->pendingRegistrationPayment($season)->count());
    }

    /** @test */
    public function providesPlayersWithoutPendingRegistrationPaymentWhenFirstYearFree()
    {
        Setting::shouldReceive('firstYearDiscount')->once()->andReturn(100);

        $season = Season::findOrFail(2);
        $this->assertEquals(9, $season->players()->withoutPendingPayment($season)->count());
    }

    /** @test */
    public function providesPlayersWithoutPendingRegistrationPaymentWhenFirstYearIsNotFree()
    {
        Setting::shouldReceive('firstYearDiscount')->once()->andReturn(75);

        DB::update('UPDATE player_season SET paid = NOW() WHERE inactive IS NULL LIMIT 1');

        $season = Season::findOrFail(2);
        $this->assertEquals(3, $season->players()->withoutPendingPayment($season)->count());
    }
}
