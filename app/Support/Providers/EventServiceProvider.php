<?php

namespace BibleBowl\Support\Providers;

use BibleBowl\Location\FetchCoordinatesForAddress;
use BibleBowl\Seasons\ProgramRegistrationPaymentReceived;
use BibleBowl\Seasons\RecordSeasonalRegistrationPayment;
use BibleBowl\Users\Auth\OnLogin;
use BibleBowl\Users\Auth\SendConfirmationEmail;
use BibleBowl\Users\Communication\AddInterestOnMailingList;
use BibleBowl\Users\Communication\RemoveInterestOnMailingList;
use BibleBowl\Users\Communication\UpdateSubscriberInformation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            OnLogin::class,
        ],
        'auth.registered' => [
            SendConfirmationEmail::class,
        ],
        'auth.resend.confirmation' => [
            SendConfirmationEmail::class,
        ],
        'user.role.added' => [
            AddInterestOnMailingList::class,
        ],
        'user.role.removed' => [
            RemoveInterestOnMailingList::class,
        ],
        'user.profile.updated' => [
            UpdateSubscriberInformation::class,
        ],
        'eloquent.created: BibleBowl\User' => [
            \BibleBowl\Users\Communication\AddToMailingList::class,
        ],
        'eloquent.created: BibleBowl\Address' => [
            FetchCoordinatesForAddress::class,
        ],
        'eloquent.updated: BibleBowl\Address' => [
            FetchCoordinatesForAddress::class,
        ],
        ProgramRegistrationPaymentReceived::EVENT => [
            RecordSeasonalRegistrationPayment::class,
        ],
        'players.registered.with.group' => [
            \BibleBowl\Groups\Communication\AddToMailingList::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
