<?php

use BibleBowl\Group;
use BibleBowl\Season;
use Illuminate\Database\Eloquent\Builder;
use BibleBowl\Player;
use BibleBowl\Users\Auth\SessionManager;

class PlayerRegistrationTest extends TestCase
{

    use \Lib\Roles\ActingAsGuardian;

    /**
     * @test
     */
    public function editExistingRegistration()
    {
        $this->setupAsGuardian();

        # seeding a player - that is registered
        $player = factory(Player::class)
            ->create([
                'guardian_id' => $this->guardian()->id
            ]);
        $player->seasons()->attach($this->season());
        $player = $this->guardian()->players()->whereHas('seasons', function (Builder $q) {
            $q->where('seasons.id', Season::first()->id);
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
    public function canFollowGroupRegistrationLinkAndBeDirectedToRegisterWithTheGroup()
    {
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

        $this->visit('/register/search/group')
            ->click('Register with '.$group->name)
            ->see($group->full_name);
    }

    /**
     * @test
     */
    public function canFollowGroupRegistrationLinkAndBeDirectedToJoinTheGroup()
    {
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

        $this->visit('/join/search/group')
            ->click('Join '.$group->name)
            ->see($group->full_name);
    }

}