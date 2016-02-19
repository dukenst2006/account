<?php

use BibleBowl\Group;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        $startingCount = $this->group()->players()->wherePivot('paid', 0)->count();
        $this->assertGreaterThan(0, $startingCount);
        $this
            ->visit('/players/pay')
            ->press('Continue')
            ->seePageIs('/cart')
            ->press('Submit')
            ->see('Payment has been received!');
        $this->assertEquals($startingCount, $this->group()->players()->wherePivot('paid', true)->count());
    }
}
