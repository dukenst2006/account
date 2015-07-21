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
    mix.styles([
        'assets/plugins/font-awesome/css/font-awesome.css',
        'assets/css/custom-icon-set.css',
        'css/style.css',
        'css/responsive.css'
    ],  'public/css/core.css', 'public');

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
        'assets/js/group.js',
        'assets/js/forms.js',
        'css/core.css'
    ]);

    // Copy font-awesome assets.
    mix.copy('public/assets/plugins/font-awesome/fonts', 'public/build/fonts', 'public');

    // Run Acceptance Tests.
    mix.task('codecept');
});

gulp.task('codecept', function() {
    var options = {
        debug: false,
        flags: '--silent --report'
    };
    gulp.src('tests/acceptance.suite.yml').pipe(codecept('./vendor/bin/codecept', options));
});
