<?php namespace BibleBowl\Support\Providers;

use Auth;
use BibleBowl\Group;
use BibleBowl\Presentation\Form;
use BibleBowl\Presentation\Html;
use Illuminate\View\View;
use Session;

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
        \View::creator('dashboard.guardian_children', function (View $view) {
            $season = Session::season();
            $view->with(
                'children',
                Auth::user()->players()
                    // eager load the current season/group
                    ->with(
                        [
                            'seasons' => function ($q) use ($season) {
                                $q->where('seasons.id', $season->id);
                            },
                            'groups' => function ($q) use ($season) {
                                $q->wherePivot('season_id', $season->id);
                            }
                        ]
                    )
                    ->get()
            );
        });

//            \View::creator('group.search', function (View $view) {
//                $view->with('searchResults', Group::where('name', 'LIKE', '%'.$request->get('q').'%')->get());
//            });
    }
}