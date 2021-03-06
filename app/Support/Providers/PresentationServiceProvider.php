<?php

namespace App\Support\Providers;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Seasons\PlayerRegistrationController;
use App\Presentation\Form;
use App\Presentation\Html;

class PresentationServiceProvider extends \Collective\Html\HtmlServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        //for EmailTemplate service provider, see AppServiceProvider

        $this->registerComposers();
    }

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new Html($app['url'], $app['view']);
        });
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new Form($app['html'], $app['url'], $app['view'], $app['session.store']->token());

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['html', 'form'];
    }

    private function registerComposers()
    {
        DashboardController::viewBindings();
        PlayerRegistrationController::viewBindings();
    }
}
