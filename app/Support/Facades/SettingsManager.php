<?php

namespace BibleBowl\Support\Facades;

use BibleBowl\Support\Settings;

class SettingsManager extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return Settings::class;
    }
}