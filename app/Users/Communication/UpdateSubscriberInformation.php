<?php namespace BibleBowl\Users\Communication;

use BibleBowl\Role;
use BibleBowl\User;
use DatabaseSeeder;
use Easychimp\Easychimp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSubscriberInformation implements ShouldQueue
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
     * @param  User  $user
     * @return void
     */
    public function handle(User $user)
    {
        if (DatabaseSeeder::isSeeding() || app()->environment('testing')) {
            return;
        }

        $list = $this->mailchimp->mailingList(env('MAILCHIMP_LIST_ID'));

        // We can't update an email address via the API, so we have to unsubscribe them
        if ($user->isDirty('email')) {
            $list->unsubscribe($user->getOriginal('email'));
        }

        // need to make sure their interests are up to date
        $interests = [];
        foreach (Role::whereNotNull('mailchimp_interest_id')->get() as $role) {
            $interests[$role->mailchimp_interest_id] = $user->hasRole($role->name);
        }

        // will add or update depending on whether the email addresses
        $list->updateSubscriber(
            $user->email,
            $user->first_name,
            $user->last_name,
            $interests
        );

        // also do this for each group
        // group members don't get classified by interest
        foreach ($user->groups()->active()->get() as $group) {
            if ($group->settings->shouldUpdateSubscribers()) {

                /** @var Easychimp $mailchimp */
                $mailchimp = app(Easychimp::class, [
                    $group->settings->mailchimpKey()
                ]);
                $list = $mailchimp->mailingList($group->settings->mailchimpListId());

                if ($user->isDirty('email')) {
                    $list->unsubscribe($user->getOriginal('email'));
                }
                $list->updateSubscriber(
                    $user->email,
                    $user->first_name,
                    $user->last_name
                );
            }
        }
    }
}
