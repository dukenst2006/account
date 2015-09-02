<?php namespace BibleBowl\Users;

use Illuminate\Support\Fluent;

class Settings extends Fluent
{
    /**
     * Determine if the user desires to be notified when a new
     * user joins the group.  Defaults to true
     */
    public function shouldBeNotifiedWhenUserJoinsGroup()
    {
        return $this->get('notifyWhenUserJoinsGroup', true);
    }

    public function notifyWhenUserJoinsGroup($newValue)
    {
        // force this value to be boolean
        return $this->notifyWhenUserJoinsGroup = !!$newValue;
    }
}