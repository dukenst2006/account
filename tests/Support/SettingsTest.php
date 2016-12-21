<?php

use Carbon\Carbon;

class ApplicationSettingsTest extends TestCase
{
    protected function tearDown()
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** @test */
    public function seasonEndStaysInCurrentYear()
    {
        Carbon::setTestNow(new Carbon('Jan 1 2016'));
        Setting::setSeasonEnd(new Carbon('July 10 2016'));

        $this->assertEquals('2016-07-10', Setting::seasonEnd()->toDateString());
    }

    /** @test */
    public function seasonEndRotatesToNextYearAfterEndingDatePassed()
    {
        Carbon::setTestNow(new Carbon('July 11 2016'));
        Setting::setSeasonEnd(new Carbon('July 10 2016'));

        $this->assertEquals('2017-07-10', Setting::seasonEnd()->toDateString());
    }

    /** @test */
    public function memoryMasterStaysInCurrentYear()
    {
        Carbon::setTestNow(new Carbon('May 1 2016'));
        Setting::setMemoryMasterDeadline(new Carbon('May 10 2016'));

        $this->assertEquals('2016-05-10', Setting::memoryMasterDeadline()->toDateString());
    }

    /** @test */
    public function memoryMasterRotatesToNextYearAfterEndingDatePassed()
    {
        Carbon::setTestNow(new Carbon('May 11 2016'));
        Setting::setMemoryMasterDeadline(new Carbon('May 10 2016'));

        $this->assertEquals('2017-05-10', Setting::memoryMasterDeadline()->toDateString());
    }
}
