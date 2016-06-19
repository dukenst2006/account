<?php

use BibleBowl\Group;
use BibleBowl\Team;
use BibleBowl\TeamSet;
use BibleBowl\Users\Auth\SessionManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupRegistrationTest extends TestCase
{

    use DatabaseTransactions;
    use \Helpers\ActingAsHeadCoach;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsHeadCoach();
    }
}
