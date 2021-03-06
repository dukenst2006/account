<?php

namespace App\Users;

use Illuminate\Support\Fluent;

class Settings extends Fluent
{
    public function __construct($attributes = [])
    {
        if ($attributes !== null) {
            parent::__construct($attributes);
        }
    }

    public function timeszone()
    {
        return $this->get('timezone', 'America/Kentucky/Louisville');
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * Determine if the user desires to be notified when a new
     * user joins the group.  Defaults to true.
     */
    public function shouldBeNotifiedWhenUserJoinsGroup() : bool
    {
        return $this->get('notifyWhenUserJoinsGroup', true);
    }

    public function notifyWhenUserJoinsGroup(bool $newValue)
    {
        // force this value to be boolean
        return $this->notifyWhenUserJoinsGroup = $newValue;
    }
}
