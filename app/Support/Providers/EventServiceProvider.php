<?php

namespace App\Support\Providers;

use App\Location\FetchCoordinatesForAddress;
use App\Seasons\ProgramRegistrationPaymentReceived;
use App\Seasons\RecordSeasonalRegistrationPayment;
use App\Users\Auth\OnLogin;
use App\Users\Auth\SendConfirmationEmail;
use App\Users\Communication\AddInterestOnMailingList;
use App\Users\Communication\RemoveInterestOnMailingList;
use App\Users\Communication\UpdateSubscriberInformation;
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
        'eloquent.created: App\User' => [
            \App\Users\Communication\AddToMailingList::class,
        ],
        'eloquent.created: App\Address' => [
            FetchCoordinatesForAddress::class,
        ],
        'eloquent.updated: App\Address' => [
            FetchCoordinatesForAddress::class,
        ],
        ProgramRegistrationPaymentReceived::EVENT => [
            RecordSeasonalRegistrationPayment::class,
        ],
        'players.registered.with.group' => [
            \App\Groups\Communication\AddToMailingList::class,
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
