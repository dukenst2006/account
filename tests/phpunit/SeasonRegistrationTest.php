<?php

use Laravel\Socialite\Two\User as ThirdPartyUser;
use BibleBowl\User;
use BibleBowl\Auth\ThirdPartyAuthenticator;
use BibleBowl\Player;

class SeasonRegistrationTest extends TestCase
{
    /** @var ThirdPartyUser */
    protected $providerUser;

    /** @var \Mockery\MockInterface */
    protected $socialite;

    /**
     * @test
     */
    public function registerWithNearbyGroup()
    {
        $player = Player::find(1);

        $this->visit('/dashboard')
            ->see($player->full_name);
    }

}