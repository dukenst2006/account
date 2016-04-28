<?php namespace BibleBowl\Http;

use BibleBowl\Http\Middleware\Authenticate;
use BibleBowl\Http\Middleware\RedirectIfAuthenticated;
use BibleBowl\Http\Middleware\RedirectIfRequiresSetup;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\Authorize;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'BibleBowl\Http\Middleware\VerifyCsrfToken',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'              => Authenticate::class,
        'auth.basic'        => AuthenticateWithBasicAuth::class,
        'guest'             => RedirectIfAuthenticated::class,
        'requires.setup'    => RedirectIfRequiresSetup::class,
        'can'               => Authorize::class
    ];
}
