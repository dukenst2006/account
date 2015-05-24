<?php namespace BibleBowl\Support\Providers;

use Whoops\Handler\PrettyPageHandler;
use BibleBowl\Presentation\EmailTemplate;
use Illuminate\Support\ServiceProvider;
use Whoops\Run;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// whoops page for local dev
//		if ($this->app->environment('production') === false) {
//			$whoops = new Run();
//			$whoops->pushHandler(new PrettyPageHandler);
//			$whoops->register();
//		}
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			\Illuminate\Contracts\Auth\Registrar::class,
			\BibleBowl\Auth\Registrar::class
		);

		// putting this in the PresentServiceProvider causes issues
		$this->app->bind('email.template', function()
		{
			return new EmailTemplate();
		});

		if ($this->app->environment('production') === false) {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
			//$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
			$this->app->register(\Spatie\Tail\TailServiceProvider::class);
		}
	}

}
