<?php

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
}
