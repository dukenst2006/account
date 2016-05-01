<?php namespace BibleBowl\Groups\Communication;

use BibleBowl\Group;
use BibleBowl\User;
use DatabaseSeeder;
use Easychimp\Easychimp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddToMailingList implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  User  $user
     * @return void
     */
    public function handle(Group $group, User $guardian)
    {
        if (DatabaseSeeder::isSeeding() || app()->environment('testing')) {
            return;
        }

        if ($group->settings->shouldUpdateSubscribers()) {

            /** @var Easychimp $mailchimp */
            $mailchimp = app(Easychimp::class, [
                $group->settings->mailchimpKey()
            ]);
            $list = $mailchimp->mailingList($group->settings->mailchimpListId());

            if ($list->isSubscribed($guardian->email) === false) {
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
