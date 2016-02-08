<?php

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Seasons\SeasonalRegistrationPaymentReceived;
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
            ->select('5', 'player[1][grade]')
            ->select('10', 'player[2][grade]')
            ->press('Continue')

            // No beginner groups exist, so we'll test joining
            // without belonging to a group
            ->click('Join Beginner Group')
            ->type('Southeast', 'q')
            ->press('Search')
            ->click('Join Later')
            ->seePageIs('/register/summary')

            // verify we're suggesting to find a group
            ->see("You didn't find a beginner group.  Do you want to")

            ->click('Join Teen Group')

            // verify nearby groups are being suggested
            ->click('#select-nearby-group-'.$nearbyGroup->id)
            ->seePageIs('/register/summary')
            ->see($nearbyGroup->name)

            ->click('Continue to payment')

            ->seePageIs('/cart');
    }

    /**
     * @test
     */
    public function canSearchForGroups()
    {
        $this->visit('/register/teen/search/group')
            ->type('Southeast', 'q')
            ->press('Search')
            ->see('Southeast Christian Church')
            ->dontSee('Mount Pleasant Christian Church');
    }

    /**
     * @test
     */
    public function cantRegisterTwice()
    {
        $guardian = $this->guardian();
        $player = Player::registeredWithNBBOnly(Season::current()->first())
            ->whereHas('guardian', function ($q) use ($guardian) {
                $q->where('id', $guardian->id);
            })->first();
        $this->visit('/register/players')
            ->dontSee($player->full_name);
    }

//
//    /**
//     * @test
//     */
//    public function editExistingRegistration()
//    {
//        # seeding a player - that is registered
//        $player = factory(Player::class)
//            ->create([
//                'guardian_id' => $this->guardian()->id
//            ]);
//        $player->seasons()->attach($this->season(), [
//            'program_id'    => Program::TEEN,
//            'grade'         => 6,
//            'shirt_size'    => 'L'
//        ]);
//        $player = $this->guardian()->players()->whereHas('seasons', function (Builder $q) {
//            $q->where('seasons.id', Season::orderBy('id', 'DESC')->first()->id);
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
//
//        # cleanup
//        $player->seasons()->sync([]);
//        $player->delete();
//    }
}
