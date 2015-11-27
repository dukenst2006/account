<?php

class InfoTest extends TestCase
{

    use \Helpers\ActingAsGuardian;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
    }

    /**
     * @test
     */
    public function canUpdateAccountDetails()
    {
        $originalPhone = $this->guardian()->phone;
        $changeToPhone = '1234567890';

        $this
            ->visit('/account/edit')
            ->see($originalPhone)
            ->type($changeToPhone, 'phone')
            ->press('Save')
            ->see('Your changes were saved')
            ->visit('/account/edit')
            ->see($changeToPhone)
            ->type($originalPhone, 'phone')
            ->press('Save');
    }

}