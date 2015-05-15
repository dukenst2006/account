var elixir = require('laravel-elixir');

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
    mix.styles([
        'css/style.css',
        'css/responsive.css',
        'assets/css/custom-icon-set.css'
    ],  'public/css/core.css', 'public');

    // compile backend assets
    mix.scripts([
        '/plugins/jquery-1.8.3.min.js',
        '/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js',
        '/plugins/boostrapv3/js/bootstrap.min.js',
        '/plugins/breakpoints.js',
        '/plugins/jquery-unveil/jquery.unveil.min.js',
        '/plugins/jquery-block-ui/jqueryblockui.js',
        '/plugins/jquery-lazyload/jquery.lazyload.min.js',
        '/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        '/plugins/jquery-numberAnimate/jquery.animateNumbers.js',
        '/js/core.js',
    ], 'public/js/backend.js', 'public/assets')

    mix.version([
        'js/backend.js',
        'css/core.css'
    ]);
});
