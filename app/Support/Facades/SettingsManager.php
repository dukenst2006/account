<?php

namespace App\Support\Facades;

use App\Support\Settings;

class SettingsManager extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return Settings::class;
    }
}
