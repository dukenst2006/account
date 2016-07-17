<?php

use BibleBowl\User;
use BibleBowl\Tournament;
use BibleBowl\RegistrationSurveyAnswer;

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
    public function canRegisterANewAccountWithoutSurvey()
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

    /**
     * @test
     */
    public function canRegisterANewAccountWithSurvey()
    {
        $email = 'actest'.time().'@testerson.com';
        $this
            ->visit('/register')
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
            ->type('40241', 'zip_code');

            $answerId = 4;
            $otherText = uniqid();
        $this
            ->check('answer[1]['.$answerId.']')

            // other
            ->check('answer[1][7]')
            ->type($otherText, 'other[1]')

            ->press('Save')

            ->seePageIs('/dashboard');

        $user = User::where('email', $email)->first();
        $otherAnswer = RegistrationSurveyAnswer::where('question_id', 1)->where('answer', 'Other')->first();
        $userSurvey = $user->surveys->get(2);

        $this->assertEquals($answerId, $user->surveys->first()->answer_id);
        $this->assertEquals($otherAnswer->id, $userSurvey->answer_id);
        $this->assertEquals($otherText, $userSurvey->other);
    }

    /**
     * @test
     */
    public function isRedirectedAfterLogin()
    {
        $tournament = Tournament::firstOrFail();

        $this
            ->visit('/login?returnUrl=tournaments/'.$tournament->slug)
            ->login(AcceptanceTestingSeeder::GUARDIAN_EMAIL, AcceptanceTestingSeeder::GUARDIAN_PASSWORD)

            ->followRedirects()
            ->seePageIs('/tournaments/'.$tournament->slug);
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
