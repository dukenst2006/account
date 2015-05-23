<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Auth\OnLogin;
use BibleBowl\Auth\UserRegistered;
use BibleBowl\Auth\SendConfirmationEmail;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

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
