<?php

use BibleBowl\User;

class NotificationsTest extends TestCase
{

    use \Helpers\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }

    /**
     * @test
     */
    public function updateNotifications()
    {
        $this
            ->visit('/account/notifications')
            ->see('Notification')->see('Preferences')
            ->uncheck('#notifyWhenUserJoinsGroup')
            ->press('Save')
            ->see('Your changes were saved');

        $userSettings = User::findOrFail($this->headCoach()->id)->settings;
        $this->assertFalse($userSettings->shouldBeNotifiedWhenUserJoinsGroup());

        $this
            ->visit('/account/notifications')
            ->check('#notifyWhenUserJoinsGroup')
            ->press('Save')
            ->see('Your changes were saved');

        $userSettings = User::findOrFail($this->headCoach()->id)->settings;
        $this->assertTrue($userSettings->shouldBeNotifiedWhenUserJoinsGroup());
    }

}