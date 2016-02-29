<?php namespace BibleBowl\Groups\Integrations\MailingList;

use BibleBowl\User;
use DatabaseSeeder;
use Easychimp\Easychimp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddToMailingList implements ShouldQueue
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

        // Allow If we're already subscribed, allow the task
        // to continue without error so it's removed from the
        // queue.  It's possible a job that adds a user to a
        // role has beat us to the punch
        if ($list->isSubscribed($user->email) === false) {
            $list->subscribe(
                $user->email,
                $user->first_name,
                $user->last_name
            );
        }
    }
}
