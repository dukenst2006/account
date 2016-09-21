<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
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
        \BibleBowl\Presentation\Html::$includeCss = [];
        \BibleBowl\Presentation\Html::$includeJs = [];
        \BibleBowl\Presentation\Html::$js = '';
        \BibleBowl\Presentation\Html::$jsData = '';
        \BibleBowl\Presentation\Html::$css = '';

        return $app;
    }
}
