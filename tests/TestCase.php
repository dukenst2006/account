<?php

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // prevent the same assets from being loaded each pageload
        \App\Presentation\Html::$includeCss = [];
        \App\Presentation\Html::$includeJs = [];
        \App\Presentation\Html::$js = '';
        \App\Presentation\Html::$jsData = '';
        \App\Presentation\Html::$css = '';

        return $app;
    }
}
