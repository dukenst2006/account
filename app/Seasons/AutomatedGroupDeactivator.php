<?php

namespace BibleBowl\Seasons;

use BibleBowl\Group;
use Illuminate\Mail\Message;
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

            Mail::queue(
                'emails.inactive-group-notification',
                [
                    'group'     => $group,
                    'season'    => $season,
                ],
                function (Message $message) use ($group) {
                    $message->to($group->owner->email, $group->owner->full_name)
                        ->subject($group->name.' Automatically Deactivated');
                }
            );
        }

        // summarize impacted groups for the office
        $deactivatedGroups = $groupsToDeactivate->count();
        if ($deactivatedGroups > 0) {
            Mail::queue(
                'emails.inactive-group-summary',
                [
                    'groupIds' => $groupsToDeactivate->modelKeys(),
                ],
                function (Message $message) use ($deactivatedGroups) {
                    $message->to(config('biblebowl.officeEmail'))
                        ->subject('Group'.($deactivatedGroups > 1 ? 's' : '').' Automatically Deactivated');
                }
            );
        }
    }
}
