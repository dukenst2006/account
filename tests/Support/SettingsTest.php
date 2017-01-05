<?php

use Carbon\Carbon;

class ApplicationSettingsTest extends TestCase
{
    /** @var int */
    protected $currentYear;

    public function setUp()
    {
        parent::setUp();

        $this->currentYear = date('Y');
    }

    protected function tearDown()
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** @test */
    public function seasonEndStaysInCurrentYear()
    {
        Carbon::setTestNow(new Carbon('Jan 1'));
        Setting::setSeasonEnd(new Carbon('July 10'));

        $this->assertEquals($this->currentYear.'-07-10', Setting::seasonEnd()->toDateString());
    }

    /** @test */
    public function seasonEndRotatesToNextYearAfterEndingDatePassed()
    {
        Carbon::setTestNow(new Carbon('July 11'));
        Setting::setSeasonEnd(new Carbon('July 10'));

        $this->assertEquals(($this->currentYear + 1).'-07-10', Setting::seasonEnd()->toDateString());
    }

    /** @test */
    public function memoryMasterStaysInCurrentYear()
    {
        Carbon::setTestNow(new Carbon('May 1'));
        Setting::setMemoryMasterDeadline(new Carbon('May 10'));

        $this->assertEquals($this->currentYear.'-05-10', Setting::memoryMasterDeadline()->toDateString());
    }

    /** @test */
    public function memoryMasterRotatesToNextYearAfterEndingDatePassed()
    {
        Carbon::setTestNow(new Carbon('July 11'));
        Setting::setMemoryMasterDeadline(new Carbon('May 10'));

        $this->assertEquals(($this->currentYear + 1).'-05-10', Setting::memoryMasterDeadline()->toDateString());
    }

    /** @test */
    public function memoryMasterDoesNotRotateYearIfStillInSeason()
    {
        Carbon::setTestNow(new Carbon('May 11'));
        Setting::setMemoryMasterDeadline(new Carbon('May 10'));

        $this->assertEquals($this->currentYear.'-05-10', Setting::memoryMasterDeadline()->toDateString());
    }
}
