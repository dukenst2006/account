<?php

namespace BibleBowl\Users\Communication;

use BibleBowl\Role;
use BibleBowl\User;
use DatabaseSeeder;
use Easychimp\Easychimp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveInterestOnMailingList implements ShouldQueue
{
    use InteractsWithQueue;

    /** @var Easychimp */
    protected $mailchimp;

    public function __construct(Easychimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    /**
     * Handle the event.
     *
     * @param User $user
     * @param Role $role
     *
     * @return void
     */
    public function handle(User $user, Role $role)
    {
        if (DatabaseSeeder::isSeeding() || app()->environment('testing')) {
            return;
        }

        $list = $this->mailchimp->mailingList(env('MAILCHIMP_LIST_ID'));

        // It's possible this recipient isn't actually subscribed
        // yet so we'll go ahead and subscribe them.
        if ($list->isOnList($user->email)) {
            $subscriberInfo = $list->subscriberInfo($user->email);

            $interests = $subscriberInfo->get('interests');
            $interests->{$role->mailchimp_interest_id} = false;

            $list->updateSubscriber(
                $user->email,
                $user->first_name,
                $user->last_name,
                $interests
            );
        } else {
            $list->subscribe(
                $user->email,
                $user->first_name,
                $user->last_name,
                [
                    $role->mailchimp_interest_id => false,
                ]
            );
        }

        $this->delete();
    }
}
