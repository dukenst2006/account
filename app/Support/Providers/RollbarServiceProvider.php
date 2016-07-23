<?php

namespace BibleBowl\Support\Providers;

use Auth;
use BibleBowl\User;
use Jenssegers\Rollbar\RollbarLogHandler;

class RollbarServiceProvider extends \Jenssegers\Rollbar\RollbarServiceProvider
{
    public function boot()
    {
        $app = $this->app;

        // Listen to log messages.
        $app['log']->listen(function ($level, $message, $context) use ($app) {

            $user = Auth::user();

            // log logged in user details
            if ($user instanceof User) {
                $context['person'] = [
                    'id'        => $user->id,
                    'username'  => $user->full_name,
                    'email'     => $user->email
                ];
            }

            $app[RollbarLogHandler::class]->log($level, $message, $context);
        });
    }
}