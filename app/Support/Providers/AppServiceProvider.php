<?php namespace BibleBowl\Support\Providers;

use Auth;
use BibleBowl\Cart;
use BibleBowl\Ability;
use BibleBowl\Presentation\EmailTemplate;
use BibleBowl\Presentation\Html;
use BibleBowl\Role;
use BibleBowl\Users\Auth\SessionManager;
use Blade;
use Monolog\Handler\LogEntriesHandler;
use URL;
use Illuminate\Support\ServiceProvider;
use Silber\Bouncer\Database\Models;
use Log;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Models::setRolesModel(Role::class);
        Models::setAbilitiesModel(Ability::class);

        if (app()->environment('production')) {
            // force production url since we're behind a load balancer
            URL::forceSchema('https');
            URL::forceRootUrl(config('app.url'));

            // Use Rollbar for exception handling
            $this->app->register(\Jenssegers\Rollbar\RollbarServiceProvider::class);
        }

        /*
         * specific library inclusion
         */
        Blade::directive('includeVueJs', function () {
            if (app()->environment('production', 'staging')) {
                return "<?php
                \\".Html::class."::\$includeJs[] = \"https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.10/vue.min.js\";
                \\".Html::class."::\$includeJs[] = \"https://cdn.jsdelivr.net/vue.validator/2.0.0-alpha.6/vue-validator.min.js\";
                ?>";
            } else {
                return "<?php
                \\".Html::class."::\$includeJs[] = \"/assets/plugins/vuejs/vue-1.0.10.min.js\";
                \\".Html::class."::\$includeJs[] = \"/assets/plugins/vuejs/vue-2.0.0-alpha.6-validator.min.js\";
                ?>";
            }
        });
        Blade::directive('includeMorris', function () {
            $html = "<?php".PHP_EOL;

            if (app()->environment('local')) {
                $html .= "\\".Html::class."::\$includeJs[] .= \"/assets/plugins/raphael/raphael-2.1.0-min.js\";";
            } else {
                $html .= "\\".Html::class."::\$includeJs[] .= \"https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js\";";
            }

            $html .= "
                \\".Html::class."::\$includeJs[] .= \"/assets/plugins/jquery-morris-chart/js/morris.min.js\";
                \\".Html::class."::\$includeCss[] .= \"/assets/plugins/jquery-morris-chart/css/morris.css\";
                ?>";

            return $html;
        });
        Blade::directive('includeGoogleCharts', function () {
            $html = "<?php".PHP_EOL;

            if (app()->environment('local')) {
                $html .= "\\".Html::class."::\$includeJs[] .= \"/assets/plugins/google-charts/loader.js\";";
            } else {
                $html .= "\\".Html::class."::\$includeJs[] .= \"https://www.gstatic.com/charts/loader.js\";";
            }

            return $html.'
                ?>';
        });
        Blade::directive('includeStripeJs', function () {
            return "<?php
                \\".Html::class."::\$includeJs[] = \"https://js.stripe.com/v2/\";
                \\".Html::class."::\$js .= \"Stripe.setPublishableKey('".getenv('STRIPE_PUBLIC_KEY')."');\"
                ?>";
        });
        Blade::directive('includeRichTextEditor', function () {
            return "<?php
                \\".Html::class."::\$includeCss[] = \"/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css\";
                \\".Html::class."::\$includeJs[] = \"/assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js\";
                \\".Html::class."::\$includeJs[] = \"/assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js\";
                ?>";
        });
        Blade::directive('includeDatePicker', function () {
            return "<?php
                \\".Html::class."::\$includeCss[] = \"/assets/plugins/bootstrap-datepicker/css/datepicker.min.css\";
                \\".Html::class."::\$includeJs[] = \"/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js\";
                ?>";
        });
        Blade::directive('includeNotifications', function () {
            return "<?php
                \\".Html::class."::\$includeCss[] = \"/css/notifications.css\";
                \\".Html::class."::\$includeJs[] = \"/js/notifications.js\";
                ?>";
        });

        /**
         * Generic reusable components
         */
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

        $this->app->singleton('session', function ($app) {
            return new SessionManager($app);
        });

        // always get the cart for the current user
        $this->app->singleton(Cart::class, function ($app) {
            return Cart::firstOrCreate([
                'user_id' => Auth::user()->id
            ]);
        });

        // putting this in the PresentServiceProvider causes issues
        $this->app->bind('email.template', function () {
            return new EmailTemplate();
        });

        if (class_exists('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        if (class_exists('Spatie\Tail\TailServiceProvider')) {
            $this->app->register(\Spatie\Tail\TailServiceProvider::class);
        }
    }
}
