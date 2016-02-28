var gulp = require('gulp'),
    elixir = require('laravel-elixir'),
    codecept = require('gulp-codeception');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //compile core css
    mix.less('style.less');
    mix.less('responsive.less');
    mix.less('themes/biblebowl/account.less');
    mix.styles([
        'assets/plugins/font-awesome/css/font-awesome.css',
        'assets/css/custom-icon-set.css',
        'css/style.css',
        'css/responsive.css'
    ],  'public/css/core.css', 'public');

    mix.scripts([
        'assets/plugins/jquery-notifications/js/messenger.js',
        'assets/plugins/jquery-notifications/js/messenger-theme-flat.js',
    ],  'public/js/notifications.js', 'public');
    mix.styles([
        'assets/plugins/jquery-notifications/css/messenger.css',
        'assets/plugins/jquery-notifications/css/messenger-theme-flat.css',
    ],  'public/css/notifications.css', 'public');

    // compile assets for managing teams
    mix.scripts([
        'assets/plugins/jquery-notifications/js/messenger.js',
        'assets/plugins/jquery-notifications/js/messenger-theme-flat.js',
        'assets/js/teamsets.js'
    ],  'public/js/teamsets.js', 'public');
    mix.scripts([
        'assets/js/group-email-settings.js'
    ],  'public/js/group-email-settings.js', 'public');
    mix.styles([
        'assets/plugins/jquery-notifications/css/messenger.css',
        'assets/plugins/jquery-notifications/css/messenger-theme-flat.css',
        'assets/css/teamsets.css'
    ],  'public/css/teamsets.css', 'public');

    // compile backend assets
    mix.scripts([
        '/assets/plugins/jquery-1.8.3.min.js',
        '/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js',
        '/assets/plugins/boostrapv3/js/bootstrap.min.js',
        '/assets/plugins/breakpoints.js',
        '/assets/plugins/jquery-unveil/jquery.unveil.min.js',
        '/assets/plugins/jquery-block-ui/jqueryblockui.js',
        '/assets/plugins/jquery-lazyload/jquery.lazyload.min.js',
        '/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        '/assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js',
        '/assets/plugins/jquery-inputmask/jquery.inputmask.min.js',
        '/assets/js/core.js',
    ], 'public/js/backend.js', 'public');

    // compile form assets
    mix.scripts([
        '/assets/plugins/jquery-inputmask/jquery.inputmask.min.js',
        '/assets/js/forms.js',
    ], 'public/js/forms.js', 'public');

    mix.version([
        'js/backend.js',
        'js/forms.js',
        'assets/js/dashboard.js',
        'assets/js/accounts.js',
        'assets/js/forms.js',
        'assets/js/payment.js',
        'css/core.css',
        'css/teamsets.css',
        'js/teamsets.js'
    ]);

    // Copy font-awesome assets.
    mix.copy('public/assets/plugins/font-awesome/fonts', 'public/build/fonts', 'public');
});
