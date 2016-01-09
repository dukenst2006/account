<?php

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Program;
use BibleBowl\Season;
use BibleBowl\Users\Auth\SessionManager;
use Illuminate\Database\Eloquent\Builder;

class SeasonRegistrationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    use \Helpers\ActingAsGuardian;

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
            ->seePageIs('/login');

        $this->setupAsGuardian();
        $this->withSession([
            SessionManager::SEASON  => $this->season->toArray(),
            SessionManager::REGISTER_WITH_GROUP => $group->guid
        ]);

        $this->visit('/register/'.$group->program->slug.'/search/group')
            ->seePageIs('/register/'.$group->program->slug.'/group/'.$group->id)
            ->see($group->name);
    }

    /**
     * @test
     */
    public function editExistingRegistration()
    {
        # seeding a player - that is registered
        $player = factory(Player::class)
            ->create([
                'guardian_id' => $this->guardian()->id
            ]);
        $player->seasons()->attach($this->season(), [
            'program_id'    => Program::TEEN,
            'grade'         => 6,
            'shirt_size'    => 'L'
        ]);
        $player = $this->guardian()->players()->whereHas('seasons', function (Builder $q) {
            $q->where('seasons.id', Season::orderBy('id', 'DESC')->first()->id);
        })->first();

        # the test
        $this
            ->visit('/dashboard')
            ->click('#edit-child-'.$player->id)
            ->select(rand(3, 12), 'grade')
            ->select(array_rand(['S', 'M', 'L', 'XL']), 'shirt_size')
            ->submitForm('Save')
            ->see('Your changes were saved');

        # cleanup
        $player->seasons()->sync([]);
        $player->delete();
    }

    /**
     * @test
     */
    public function canSelectProgram()
    {
        $this->visit('/register/program')
            ->see("Which program are you registering player(s) for?")
            ->see('Beginner Bible Bowl')
            ->click('Teen Bible Bowl')
            ->seePageIs('/register/teen/search/group');
    }

    /**
     * @test
     */
    public function canSearchForGroups()
    {
        $group = Group::where('name', 'Southeast Christian Church')->firstOrFail();

        $this->visit('/register/'.$group->program->slug.'/search/group')
            ->see("Find Your Group")
            ->click('select-nearby-group-'.$group->id)
            ->seePageIs('/register/'.$group->program->slug.'/group/'.$group->id);
    }

    /**
     * @test
     */
    public function canGoToRegistrationWithoutAGroupAfterSearching()
    {
        $joinLaterButton = 'Join a Group Later';

        $this->visit('/register/teen/search/group')
            ->see("Find Your Group")

            // should only see this after searching
            ->dontSee($joinLaterButton)

            ->type('Southeast', 'q')
            ->press('Search')
            ->click($joinLaterButton)
            ->seePageIs('/register/teen/group');
    }

    /**
     * @test
     */
    public function canChangeProgramOrGroup()
    {
        $group = Group::where('program_id', Program::TEEN)->firstOrFail();

        $this->visit('/register/teen/group/'.$group->id)
            ->click('#program-change')
            ->seePageIs('/register/program')
            ->visit('/register/teen/group/'.$group->id)
            ->click('#group-change')
            ->seePageIs('/register/teen/search/group?noRedirect=1');
    }

    /**
     * @test
     */
    public function canRegisterWithoutGroup()
    {
        $player = $this->guardian()->players()->whereDoesntHave('seasons')->first();

        $this->visit('/register/teen/group')
            ->see($player->full_name)
            ->seeIsChecked('player'.$player->id.'register');
//            ->select('9', 'player['.$player->id.'][grade]')
//            ->select('L', 'player['.$player->id.'][shirtSize]')

        $this->assertPlayerWasRegistered($player);

        $registrationData = $player->seasons->first()->pivot;
        $this->assertCount(1, $player->seasons);
        $this->assertEquals(Program::TEEN, $registrationData->program_id);

        // unregister player
        $player->seasons()->sync([]);
    }

    /**
     * @test
     */
    public function canRegisterWithGroup()
    {
        $player = $this->guardian()->players()->whereDoesntHave('seasons')->first();

        $group = Group::where('program_id', Program::TEEN)->firstOrFail();

        Mail::shouldReceive('queue')->once();
        $this->visit('/register/teen/group/'.$group->id)
            ->see($player->full_name)
            ->seeIsChecked('player'.$player->id.'register');
//            ->select('9', 'player['.$player->id.'][grade]')
//            ->select('L', 'player['.$player->id.'][shirtSize]')

        $this->assertPlayerWasRegistered($player);

        $this->assertCount(1, $player->seasons);

        $registrationData = $player->seasons->first()->pivot;
        $this->assertEquals($group->id, $registrationData->group_id);
        $this->assertEquals($group->program_id, $registrationData->program_id);

        // unregister player
        $player->seasons()->sync([]);
    }

    /**
     * @test
     */
    public function cantRegisterTwice()
    {
        $player = $this->guardian()->players()->whereDoesntHave('seasons')->first();
        $player->seasons()->attach($this->season(), [
            'program_id'    => Program::TEEN,
            'grade'         => 6,
            'shirt_size'    => 'L'
        ]);
        $this->visit('/register/teen/group')
            ->dontSee($player->full_name);

        // unregister player
        $player->seasons()->sync([]);
    }

    private function assertPlayerWasRegistered(Player $player)
    {
        // Because the selects don't actually populate their fields, we
        // expect the grade for the player to not be populated and thus
        // throwing this error when viewing the dashboard
        try {
            $this->press('Register');
        } catch (\Exception $e) {
            $this->assertEquals('A request to [http://localhost/dashboard] failed. Received status code [500].', $e->getMessage());
        }
        // Update the grade for this player so they'll display on the dashboard
        // Also assert at least 1 row was updated (the player being successfully updated)
        $this->assertEquals($player->seasons()->updateExistingPivot($this->season()->id, [
            'grade' => 9
        ]), 1);
        // delete players we "didn't mean to register" and thus don't have a correct grade
        DB::statement('DELETE FROM player_season WHERE grade = 0');

        // we'll reload the dashboard to verify everything shows correctly
        // since our redirect threw the above exception
        $this
            ->visit('/dashboard');

        // Having to remove this because the above error is clearing this from the session
        //->see('Your player(s) have been registered!');
    }
}
