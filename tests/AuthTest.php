<?php

use BibleBowl\User;
use BibleBowl\Users\Auth\ThirdPartyAuthenticator;
use Laravel\Socialite\Two\User as ThirdPartyUser;

class AuthTest extends TestCase
{
    use \Helpers\ActingAsGuardian;

    protected $password = 'asdfasdf';

    /**
     * @test
     */
    public function canLogout()
    {
        $this->setupAsGuardian();

        $this
            ->visit('logout')
            ->seePageIs('/login');
    }

    /**
     * @test
     */
    public function canConfirmEmailAddress()
    {
        $user = User::where('email', AcceptanceTestingSeeder::UNCONFIRMED_USER_EMAIL)->first();

        $this
            ->login($user->email)
            ->see('Your email address is not yet confirmed.')

            ->visit('/register/confirm/'.$user->guid)
            ->see('Your email address has been confirmed, you may now login')
            ->login($user->email)
            ->seePageIs('/dashboard');

        // reset the user back to unconfirmed
        DB::statement('UPDATE users SET status = 0 WHERE id = ?', [
            $user->id
        ]);
    }

    /**
     * @test
     */
    public function canRegisterANewAccount()
    {
        $email = 'actest'.time().'@testerson.com';
        $this
            ->visit('/login')
            ->click('register a new account')
            ->type($email, 'email')
            ->type($this->password, 'password')
            ->type($this->password, 'password_confirmation')
            ->press('Register')

            // proceeds to account setup
            ->seePageIs('/account/setup')
            ->type('Johnson', 'first_name')
            ->type('Johnson', 'last_name')
            ->type('1234567890', 'phone')
            ->type('123 Test Street', 'address_one')
            ->type('Apt 1', 'address_two')
            ->type('40241', 'zip_code')
            ->press('Save')

            ->seePageIs('/dashboard');

        // assert user was created with a primary email address
        $user = User::where('email', $email)->first();
        $this->assertTrue($user->exists);
        $this->assertNotNull($user->primary_address_id);
    }

    private function login($email, $password = AcceptanceTestingSeeder::USER_PASSWORD)
    {
        $this
            ->visit('/login')
            ->type($email, 'email')
            ->type($password, 'password')
            ->press('Login');

        return $this;
    }


}