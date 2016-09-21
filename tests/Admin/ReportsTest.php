<?php


class ReportsTest extends TestCase
{
    protected $user;

    use \Helpers\ActingAsDirector;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();
    }

    /**
     * @test
     */
    public function canViewGrowthReports()
    {
        $this->visit('/admin/reports/growth');
    }

    /**
     * @test
     */
    public function canViewSeasonReports()
    {
        $this->visit('/admin/reports/seasons');
    }

    /**
     * @test
     */
    public function canViewRegistrationSurveyReports()
    {
        $this->visit('/admin/reports/registration-surveys');
    }
}
