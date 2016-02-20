<?php

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Program;
use BibleBowl\Season;
use BibleBowl\Users\Auth\SessionManager;

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
    public function followingRegistrationLinkDefaultsToSelectedGroup()
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

        $this->visit('/register/players')
            ->select('12', 'player[1][grade]')
            ->press('Continue')
            ->see($group->name);
    }

    /**
     * @test
     */
    public function canRegisterPlayers()
    {
        $this->visit('/dashboard')
            ->click('Register')
            ->select('5', 'player[1][grade]')
            ->select('10', 'player[2][grade]')
            ->press('Continue')

            // make sure there's no option to continue until
            // we've searched for groups
            ->dontSee('Continue to payment')

            // verify it sorted the players into each program
            ->see('Join Beginner Group')
            ->see('Join Teen Group');
    }

    /**
     * @test
     */
    public function canRegisterPlayersForAllPrograms()
    {
        $nearbyGroup = Group::where('name', DatabaseSeeder::GROUP_NAME)->firstOrFail();

        $this->visit('/register/players')
            ->see('David Webb')
            ->see('Ethan Smith')
            ->select('11', 'player[1][grade]')
            ->select('10', 'player[2][grade]')
            ->press('Continue')
            ->dontSee('Submit Registration')

            // can't find a beginner group, so register without them
            ->click('Join Beginner Group')
            ->type('Southeast', 'q')
            ->press('Search')
            ->click('Register Later')
            ->seePageIs('/register/summary')
            ->see('Your Beginner players have been removed from this registration')
            ->dontSee('Beginner Bible Bowl');

        // verify beginner players have been removed
        $beginner = Program::findOrFail(Program::BEGINNER);
        /** @var \BibleBowl\Seasons\GroupRegistration $registration */
        $registration = Session::groupRegistration();
        $this->assertEquals(0, $registration->numberOfPlayers($beginner));

        $this
            ->click('Join Teen Group')

            // verify nearby groups are being suggested
            ->click('#select-nearby-group-'.$nearbyGroup->id)
            ->seePageIs('/register/summary')
            ->see($nearbyGroup->name)

            ->click('Submit Registration')
            ->see('Your registration has been submitted!')

            // verify we can't re-register those players
            ->visit('/register/players')
            ->dontSee('David Webb')
            ->dontSee('Ethan Smith');

    }

//    /**
//     * @test
//     */
//    public function canSearchForGroups()
//    {
//        $this->visit('/register/teen/search/group')
//            ->type('Southeast', 'q')
//            ->press('Search')
//            ->see('Southeast Christian Church')
//            ->dontSee('Mount Pleasant Christian Church');
//    }
//
//    /**
//     * @test
//     */
//    public function cantRegisterTwice()
//    {
//        $guardian = $this->guardian();
//        $player = Player::registeredWithNBBOnly(Season::current()->first())
//            ->whereHas('guardian', function ($q) use ($guardian) {
//                $q->where('id', $guardian->id);
//            })->first();
//        $this->visit('/register/players')
//            ->dontSee($player->full_name);
//    }
//
//    /**
//     * @test
//     */
//    public function editExistingRegistration()
//    {
//        $player = $this->guardian()->players()->whereHas('seasons', function (Builder $q) {
//            $q->where('seasons.id', Season::current()->first()->id);
//        })->first();
//
//        # the test
//        $this
//            ->visit('/dashboard')
//            ->click('#edit-child-'.$player->id)
//            ->select(rand(3, 12), 'grade')
//            ->select(array_rand(['S', 'M', 'L', 'XL']), 'shirt_size')
//            ->submitForm('Save')
//            ->see('Your changes were saved');
//    }
}
