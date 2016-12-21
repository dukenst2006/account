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
        $this
            ->visit('/memory-master')
            ->check('player[6]')
            ->press('Save')
            ->see('Your changes have been saved')
            ->seeIsChecked('player[6]');
    }

    /** @test */
    public function cantChangeMemoryMasterAfterDeadline()
    {
        Carbon::setTestNow(Carbon::now()->addYear());
        $this
            ->visit('/memory-master')
            ->see('The deadline for submitting Memory Master achievers has passed');
    }
}
