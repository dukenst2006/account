<?php

use BibleBowl\Program;
use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\Player;
use BibleBowl\Users\Auth\SessionManager;

class GroupJoinTest extends TestCase
{

    use \Lib\Roles\ActingAsGuardian;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
        $this->withSession([
            SessionManager::SEASON  => $this->season->toArray()
        ]);
    }

    /**
     * @test
     */
    public function followingRegistrationLinkSkipsGroupSelection()
    {
        // since only the guardian login is seeded, we can tear down
        // to "log out"
        $this->tearDown();
        parent::setUp();

        $group = Group::where('name', DatabaseSeeder::GROUP_NAME)->first();

        # following the link takes you to login
        $this
            ->visit($group->registrationReferralLink())
            ->followRedirects()
            ->landOn('/login');

        $this->setupAsGuardian();
        $this->withSession([
            SessionManager::SEASON  => $this->season->toArray(),
            SessionManager::REGISTER_WITH_GROUP => $group->guid
        ]);

        $this->visit('/join/'.$group->program->slug.'/search/group')
            ->landOn('/join/'.$group->program->slug.'/group/'.$group->id)
            ->see($group->name);
    }

    /**
     * @test
     */
    public function canSearchForGroups()
    {
        $group = Group::where('name', 'Southeast Christian Church')->firstOrFail();

        $this->visit('/join/'.$group->program->slug.'/search/group')
            ->see("Find Your Group")
            ->click('select-nearby-group-'.$group->id)
            ->landOn('/join/'.$group->program->slug.'/group/'.$group->id);
    }

    /**
     * @test
     */
    public function canChangeGroup()
    {
        $group = Group::where('program_id', Program::TEEN)->firstOrFail();

        $this->visit('/join/teen/group/'.$group->id)
            ->dontSeeLink('/join/program')
            ->click('#group-change')
            ->landOn('/join/teen/search/group?noRedirect=1');
    }

    /**
     * @test
     */
    public function canJoinGroup()
    {
        $player = $this->guardian()->players()->has('seasons')->first();
        $group = Group::where('program_id', Program::TEEN)->firstOrFail();

        $this->visit('/join/teen/group/'.$group->id)
            ->see($player->full_name)
            ->seeIsChecked('player'.$player->id.'register')
            ->press('Join')
            ->landOn('/dashboard')
            ->see('Your player(s) have joined a group!');

        $this->assertCount(1, $player->seasons);

        $registrationData = $player->seasons->first()->pivot;
        $this->assertEquals($group->id, $registrationData->group_id);
        $this->assertEquals($group->program_id, $registrationData->program_id);

        // unjoin player
        DB::statement('UPDATE player_season SET group_id = NULL WHERE player_id = ? AND group_id = ?', [
            $player->id,
            $group->id
        ]);
    }

    /**
     * @test
     */
    public function cantJoinTwice()
    {
        $group = Group::where('program_id', Program::TEEN)->firstOrFail();
        $player = $this->guardian()->players->first();
        $player->seasons()->attach($this->season(), [
            'group_id'      => $group->id,
            'program_id'    => Program::TEEN,
            'grade'         => 6,
            'shirt_size'    => 'L'
        ]);
        $this->visit('/join/teen/group/'.$group->id)
            ->dontSee($player->full_name);

        // unjoin player
        $player->seasons()->sync([]);
    }

}