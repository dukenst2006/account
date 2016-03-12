<?php

use BibleBowl\User;

class SettingsTest extends TestCase
{

    protected $user;

    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /**
     * @test
     */
    public function searchUserList()
    {
        $this
            ->visit('/admin/settings')
            ->see('Settings')
            ->press('Save')
            ->see('Your changes were saved');
    }
}
