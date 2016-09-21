<?php

use BibleBowl\Seasons\SeasonRotator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RotatorTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function deactivatesGroups()
    {
        $birthday = Mockery::mock(Carbon::class);
        $birthday->shouldReceive('isBirthday')->withNoArgs()->andReturn(true);
        $birthday->shouldIgnoreMissing();
        Setting::shouldReceive('seasonEnd')->andReturn($birthday);
        Setting::shouldReceive('seasonStart')->andReturn(Carbon::now());

        Artisan::call(SeasonRotator::COMMAND);
    }
}
