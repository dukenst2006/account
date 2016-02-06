<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Location\FetchCoordinatesForAddress;
use BibleBowl\Seasons\SeasonalRegistrationPaymentReceived;
use BibleBowl\Seasons\RegisterWithNationalOffice;
use BibleBowl\Seasons\RegisterWithGroup;
use BibleBowl\Users\Auth\OnLogin;
use BibleBowl\Users\Auth\SendConfirmationEmail;
use BibleBowl\Users\Communication\AddInterestOnMailingList;
use BibleBowl\Users\Communication\AddToMailingList;
use BibleBowl\Users\Communication\RemoveInterestOnMailingList;
use BibleBowl\Users\Communication\UpdateSubscriberInformation;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'auth.login' => [
            OnLogin::class,
        ],
        'auth.registered' => [
            SendConfirmationEmail::class
        ],
        'auth.resend.confirmation' => [
            SendConfirmationEmail::class
        ],
        'user.role.added' => [
            AddInterestOnMailingList::class
        ],
        'user.role.removed' => [
            RemoveInterestOnMailingList::class
        ],
        'user.profile.updated' => [
            UpdateSubscriberInformation::class
        ],
        'eloquent.created: BibleBowl\User' => [
            AddToMailingList::class
        ],
        'eloquent.created: BibleBowl\Address' => [
            FetchCoordinatesForAddress::class
        ],
        'eloquent.updated: BibleBowl\Address' => [
            FetchCoordinatesForAddress::class
        ],
        SeasonalRegistrationPaymentReceived::EVENT => [
            RegisterWithNationalOffice::class,
            RegisterWithGroup::class
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
