<?php

use BibleBowl\User;

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
    public function canViewPlayersReports()
    {
        $this->visit('/admin/reports/players');
    }
}
