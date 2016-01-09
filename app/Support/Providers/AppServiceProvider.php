<?php namespace BibleBowl\Support\Providers;

use BibleBowl\Presentation\EmailTemplate;
use BibleBowl\Presentation\Html;
use BibleBowl\Team;
use BibleBowl\Users\Auth\SessionManager;
use Blade;
use Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('includeCss', function ($path) {
            if (str_contains($path, 'elixir')) {
                return "<?php \\".Html::class."::\$includeCss[] = ".$path."; ?>";
            }
            return "<?php \\".Html::class."::\$includeCss[] = \"".trim($path, '()')."\"; ?>";
        });
        Blade::directive('includeJs', function ($path) {
            if (str_contains($path, 'elixir')) {
                return "<?php \\".Html::class."::\$includeJs[] = ".$path."; ?>";
            }
            return "<?php \\".Html::class."::\$includeJs[] = \"".trim($path, '()')."\"; ?>";
        });
        Blade::directive('js', function () {
            return "<?php ob_start(); ?>";
        });
        Blade::directive('endjs', function () {
            return "<?php \\".Html::class."::\$js .= ob_get_clean(); ?>";
        });

        Blade::directive('jsData', function () {
            return "<?php ob_start(); ?>";
        });
        Blade::directive('endjsData', function () {
            return "<?php \\".Html::class."::\$jsData .= ob_get_clean(); ?>";
        });

        Blade::directive('css', function () {
            return "<?php ob_start(); ?>";
        });
        Blade::directive('endcss', function () {
            return "<?php \\".Html::class."::\$css .= ob_get_clean(); ?>";
        });
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
            \BibleBowl\Users\Auth\Registrar::class
        );

        $this->app->singleton(
            'session',
            function ($app) {
                return new SessionManager($app);
            }
        );

        // putting this in the PresentServiceProvider causes issues
        $this->app->bind('email.template', function () {
            return new EmailTemplate();
        });

        if (Config::get('app.debug') === true) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Spatie\Tail\TailServiceProvider::class);
        }
    }
}
