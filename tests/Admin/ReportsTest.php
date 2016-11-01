<?php

use Carbon\Carbon;

class ReportsTest extends TestCase
{
    protected $user;

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

    /** @test */
    public function canViewFinancialReports()
    {
        $this->visit('/admin/reports/financials')
            ->see(Carbon::now()->subMonths(2)->format('M'))

            ->see(Carbon::now()->subMonth()->format('M'))
            ->see('$75')

            ->see('$50');
    }

    /** @test */
    public function canViewRegistrationSurveyReports()
    {
        $this->visit('/admin/reports/registration-surveys');
    }
}
