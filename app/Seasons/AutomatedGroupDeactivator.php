<?php

namespace App\Seasons;

use App\Group;
use App\Groups\DeactivatedNotification;
use App\Groups\DeactivatedSummary;
use Mail;

class AutomatedGroupDeactivator
{
    /**
     * @param array $groups
     */
    public function deactivateInactiveGroups($season)
    {
        $groupsToDeactivate = Group::active($season)->withoutActivePlayers($season)->get();
        // notify the group owner
        foreach ($groupsToDeactivate as $group) {
            $group->update([
                'inactive' => 1, // will convert to a timestamp on save
            ]);

            Mail::to($group->owner)->queue(new DeactivatedNotification($group, $season));
        }

        // summarize impacted groups for the office
        $deactivatedGroups = $groupsToDeactivate->count();
        if ($deactivatedGroups > 0) {
            Mail::to(config('biblebowl.officeEmail'))->queue(new DeactivatedSummary($groupsToDeactivate->modelKeys()));
        }
    }
}
