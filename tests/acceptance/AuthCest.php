<?php

class AuthCest
{
    protected $firstName = 'Bill';
    protected $lastName = 'Johnson';
    protected $email = null;
    protected $password = 'asdfasdf';

    public function tryToRegister(AcceptanceTester $I)
    {
        # Seed email
        $this->email = 'actest'.time().'@testerson.com';

        $I->amOnPage('/login');

        $I->click('Sign up Now!');

        $I->fillField('first_name', $this->firstName);
        $I->fillField('last_name', $this->lastName);
        $I->fillField('email', $this->email);
        $I->fillField('password', $this->password);
        $I->fillField('password_confirmation', $this->password);

        $I->click('Register');

        $I->canSeeCurrentUrlEquals('/dashboard');
    }

    /**
     * @depends tryToRegister
     */
    public function tryToLogout(AcceptanceTester $I)
    {
        $I->amOnPage('/dashboard');

        $I->click('#user-options');
        $I->click('Log Out');

        $I->canSeeCurrentUrlEquals('/login');
    }

    /**
     * @depends tryToLogout
     */
    public function tryToLogin(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('email', $this->email);
        $I->fillField('password', $this->password);

        $I->click('Login');

        $I->canSeeCurrentUrlEquals('/dashboard');
    }
}