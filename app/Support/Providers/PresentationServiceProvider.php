<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Presentation\Form;
use BibleBowl\Presentation\Html;

class PresentationServiceProvider extends \Illuminate\Html\HtmlServiceProvider
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

        $this->app->bindShared('html', function($app)
        {
            return new Html($app['url']);
        });

        $this->app->bindShared('form', function($app)
        {
            $form = new Form($app['html'], $app['url'], $app['session.store']->getToken());
            return $form->setSessionStore($app['session.store']);
        });

        //for EmailTemplate service provider, see AppServiceProvider
    }
}