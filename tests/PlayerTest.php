<?php

namespace BibleBowl;

use Setting;
use TestCase;
use DB;

class PlayerTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    /** @test */
    public function providesPlayersPendingRegistrationPayment()
    {
        Setting::shouldReceive('firstYearFree')->once()->andReturn(false);

        $season = Season::findOrFail(2);

        $this->assertEquals(2, $season->players()->pendingRegistrationPayment($season)->count());
    }

    /** @test */
    public function excludesFirstYearPlayersFromPendingRegistrationPayments()
    {
        Setting::shouldReceive('firstYearFree')->once()->andReturn(true);

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
        Setting::shouldReceive('firstYearFree')->once()->andReturn(true);

        $season = Season::findOrFail(2);
        $this->assertEquals(3, $season->players()->withoutPendingPayment($season)->count());
    }

    /** @test */
    public function providesPlayersWithoutPendingRegistrationPaymentWhenFirstYearIsNotFree()
    {
        Setting::shouldReceive('firstYearFree')->once()->andReturn(false);

        DB::update('UPDATE player_season SET paid = NOW() WHERE inactive IS NULL LIMIT 1');

        $season = Season::findOrFail(2);
        $this->assertEquals(1, $season->players()->withoutPendingPayment($season)->count());
    }
}
