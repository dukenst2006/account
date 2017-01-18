<?php

use BibleBowl\Address;
use BibleBowl\User;
use BibleBowl\Users\Auth\ThirdPartyAuthenticator;
use Laravel\Socialite\Two\User as ThirdPartyUser;

class ThirdPartyAuthTest extends TestCase
{
    /** @var ThirdPartyUser */
    protected $providerUser;

    /** @var \Mockery\MockInterface */
    protected $socialite;

    public function setUp()
    {
        parent::setUp();

        //simulate what we would get from an OAuth2 provider
        $this->providerUser = new ThirdPartyUser();
        $this->providerUser->map([
            'id'            => uniqid(),
            'name'          => 'John Peterson',
            'email'         => 'john.'.uniqid().'@peterson.example.com',
            'avatar'        => 'avatar'.time().'.jpg',
        ]);
        $this->socialite = Mockery::mock(\Laravel\Socialite\SocialiteManager::class);
        $this->socialite->shouldReceive('driver')->andReturn($this->socialite);
        $this->socialite->shouldReceive('user')->andReturn($this->providerUser);
        $this->app->instance(\Laravel\Socialite\Contracts\Factory::class, $this->socialite);
    }

    /**
     * @test
     */
    public function loginViaOAuth2()
    {
        Mail::shouldReceive('send');
        $this->expectsEvents('auth.registered');

        $this->call('GET', '/login/'.ThirdPartyAuthenticator::PROVIDER_GOOGLE, [
            'code' => uniqid(),
        ]);

        // verify requires setup since there's no mailing address
        $this->assertRedirectedTo('/account/setup');

        //assert user created
        $name = explode(' ', $this->providerUser->getName());
        $createdUser = User::where('first_name', $name[0])
            ->where('last_name', $name[1])
            ->where('email', $this->providerUser->getEmail())
            ->where('avatar', $this->providerUser->getAvatar())
            ->first();
        $this->assertGreaterThan(0, $createdUser->id);

        //assert OAuth id is linked to user
        $userProvider = $createdUser->providers->first();
        $this->assertEquals(ThirdPartyAuthenticator::PROVIDER_GOOGLE, $userProvider->provider);
        $this->assertEquals($this->providerUser->getId(), $userProvider->provider_id);
    }

    /**
     * @test
     */
    public function associateWithExistingUserIfEmailAddressMatches()
    {
        $firstName = 'Jane';
        $lastName = 'Lork';
        //allow GUID to be set
        User::unguard();
        User::create([
            'guid'                  => md5(uniqid().microtime()),
            'email'                 => $this->providerUser->getEmail(),
            'first_name'            => $firstName,
            'last_name'             => $lastName,
            'primary_address_id'    => Address::firstOrFail()->id,
        ]);
        User::reguard();

        $this->call('GET', '/login/'.ThirdPartyAuthenticator::PROVIDER_GOOGLE, [
            'code' => uniqid(),
        ]);

        // logged in as pre-existing user
        $this->followRedirects()
            ->see($firstName.' '.$lastName);

        //assert user was not created
        $name = explode(' ', $this->providerUser->getName());
        $createdUser = User::where('first_name', $name[0])
                ->where('last_name', $name[1])
                ->where('email', $this->providerUser->getEmail())
                ->where('avatar', $this->providerUser->getAvatar())
                ->count() > 0;
        $this->assertFalse($createdUser);
    }

    /** @test */
    public function requiresEmailAddress()
    {
        //simulate what we would get from an OAuth2 provider
        $this->providerUser = new ThirdPartyUser();
        $this->providerUser->map([
            'id'            => uniqid(),
            'name'          => 'John Peterson',
            'email'         => '',
            'avatar'        => 'avatar'.time().'.jpg',
        ]);
        $this->socialite = Mockery::mock('Laravel\Socialite\SocialiteManager');
        $this->socialite->shouldReceive('driver')->andReturn($this->socialite);
        $this->socialite->shouldReceive('user')->andReturn($this->providerUser);
        $this->app->instance('Laravel\Socialite\Contracts\Factory', $this->socialite);

        $this->call('GET', '/login/'.ThirdPartyAuthenticator::PROVIDER_GOOGLE, [
            'code' => uniqid(),
        ]);

        // logged in as pre-existing user
        $this->followRedirects()
            ->see("We couldn't get your email address from that account");

        //assert user was not created
        $name = explode(' ', $this->providerUser->getName());
        $createdUser = User::where('first_name', $name[0])
                ->where('last_name', $name[1])
                ->where('email', $this->providerUser->getEmail())
                ->where('avatar', $this->providerUser->getAvatar())
                ->count() > 0;
        $this->assertFalse($createdUser);
    }
}
