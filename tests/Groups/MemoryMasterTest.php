<?php

use Carbon\Carbon;

class MemoryMasterTest extends TestCase
{
    use \Helpers\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /** @test */
    public function changeMemoryMaster()
    {
        Carbon::setTestNow(new Carbon('Feb 2nd '.date('Y')));

        $this
            ->visit('/memory-master')
            ->check('player[9]')
            ->press('Save')
            ->see('Your changes have been saved')
            ->seeIsChecked('player[9]');
    }

    /** @test */
    public function cantChangeMemoryMasterAfterDeadline()
    {
        Carbon::setTestNow(new Carbon('May 2nd '.date('Y')));

        $this
            ->visit('/memory-master')
            ->see('The deadline for submitting Memory Master achievers has passed');
    }
}
