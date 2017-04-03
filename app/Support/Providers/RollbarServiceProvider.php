<?php

namespace App\Support\Providers;

use App\User;
use Auth;
use Illuminate\Log\Events\MessageLogged;
use Jenssegers\Rollbar\RollbarLogHandler;

class RollbarServiceProvider extends \Jenssegers\Rollbar\RollbarServiceProvider
{
    public function boot()
    {
        $app = $this->app;

        // Listen to log messages.
        $app['log']->listen(function (MessageLogged $message) use ($app) {
            $user = Auth::user();

            // log logged in user details
            if ($user instanceof User) {
                $message->context['person'] = [
                    'id'        => $user->id,
                    'username'  => $user->full_name,
                    'email'     => $user->email,
                ];
            }

            $app[RollbarLogHandler::class]->log($message->level, $message->message, $message->context);
        });
    }
}
