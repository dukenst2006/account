<?php

namespace App\Groups\Communication;

use App\Group;
use App\User;
use DatabaseSeeder;
use Easychimp\Easychimp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mailchimp\Mailchimp;

class AddToMailingList implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param User $user
     *
     * @return void
     */
    public function handle(Group $group, User $guardian)
    {
        if (DatabaseSeeder::isSeeding() || app()->environment('testing')) {
            return;
        }

        if ($group->settings->shouldUpdateSubscribers()) {
            $mailchimp = new Easychimp(new Mailchimp($group->settings->mailchimpKey()));
            $list = $mailchimp->mailingList($group->settings->mailchimpListId());

            if ($list->isOnList($guardian->email) === false) {
                $list->subscribe(
                    $guardian->email,
                    $guardian->first_name,
                    $guardian->last_name
                );
            }
        }

        $this->delete();
    }
}
