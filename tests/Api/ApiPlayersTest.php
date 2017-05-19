<?php

use App\Group;

class ApiPlayersTest extends TestCase
{
    protected $group;

    use \Helpers\ActingAsDirector;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsDirector();

        $this->group = Group::where('name', DatabaseSeeder::GROUP_NAME)->first();
    }

    /**
     * @test
     */
    public function rejectsUnauthorizedAccessorsForScoremaster()
    {
        $this
            ->get('/api/'.time().'/players/scoremaster')
            ->assertResponseStatus(403);
    }

    /** @test */
    public function providesPlayersForScoremaster()
    {
        $this
            ->visit('/api/'.getenv('SCOREMASTER_TOKEN').'/players/scoremaster')
            ->assertResponseOk();

        $csvContents = $this->response->getContent();

        $this->assertContains('Southeast Christian Church', $csvContents);
        $this->assertContains('Teen', $csvContents);
    }
}
