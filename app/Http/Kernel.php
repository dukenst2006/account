<?php

namespace App\Http;

use App\Http\Middleware\AuthenticateApiToken;
use App\Http\Middleware\RedirectIfRequiresSetup;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The BibleBowllication's global HTTP middleware stack.
     *
     * These middleware are run during every request to your BibleBowllication.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        //\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The BibleBowllication's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            'tokenAuth',
        ],
    ];

    /**
     * The BibleBowllication's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'           => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'     => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'       => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'            => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'          => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'       => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'requires.setup' => RedirectIfRequiresSetup::class,
        'tokenAuth'      => AuthenticateApiToken::class,
    ];
}
