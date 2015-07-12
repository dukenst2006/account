<?php namespace BibleBowl\Support\Providers;

use Auth;
use BibleBowl\Group;
use BibleBowl\Presentation\Form;
use BibleBowl\Presentation\Html;
use View;

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

        $this->registerComposers();
    }

    private function registerComposers()
    {
        // on the dashboard, when a user has children load the nearby groups
        View::creator('dashboard.group_registration', function ($view) {
            // "First" address is a bit random, the user should control this
            $view->with('nearbyGroups', Group::nearby(Auth::user()->addresses->first())->get());
        });
    }
}