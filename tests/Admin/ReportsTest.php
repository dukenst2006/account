<?php

use App\Player;
use App\Season;
use Carbon\Carbon;

class ReportsTest extends TestCase
{
    protected $user;

    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /** @test */
    public function canViewGrowthReports()
    {
        $this->visit('/admin/reports/growth');
    }

    /** @test */
    public function canViewSeasonReports()
    {
        $this->visit('/admin/reports/seasons');
    }

//    /** @test */
//    public function canViewFinancialReports()
//    {
//        $this->visit('/admin/reports/financials')
//            ->see(Carbon::now()->subMonths(2)->format('M'))
//
//            ->see(Carbon::now()->subMonth()->format('M'))
//            ->see('$75')
//
//            ->see('$50');
//    }

    /** @test */
    public function canViewRegistrationSurveyReports()
    {
        $this->visit('/admin/reports/registration-surveys');
    }

    /** @test */
    public function canExportMemoryMasterAchievers()
    {
        $currentSeason = Season::current()->firstOrFail();
        $player = $currentSeason->players()->firstOrFail();
        $currentSeason->players()->updateExistingPivot($player->id, [
            'memory_master' => 1,
        ]);
        $player = Player::achievedMemoryMaster($currentSeason)->firstOrFail();

        ob_start();
        $this
            ->visit('/admin/reports/export-memory-master/'.\App\Program::TEEN.'?seasonId='.$currentSeason->id)
            ->assertResponseOk();

        $csvContents = ob_get_contents();
        ob_end_clean();

        $this->assertContains($player->first_name, $csvContents);
        $this->assertContains($player->last_name, $csvContents);
    }
}
