<?php

class AuthCest
{
    protected $firstName = 'Bill';
    protected $lastName = 'Johnson';
    protected $email = null;
    protected $password = 'asdfasdf';

    public function registerNewAccount(AcceptanceTester $I)
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

        $I->see($this->firstName.' '.$this->lastName);
    }

    /**
     * @depends registerNewAccount
     */
    public function logout(AcceptanceTester $I)
    {
        $I->amOnPage('/dashboard');

        \Lib\AuthHelper::logout($I);

        $I->canSeeCurrentUrlEquals('/login');
    }

    /**
     * @depends logout
     */
    public function login(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('email', $this->email);
        $I->fillField('password', $this->password);

        $I->click('Login');

        $I->canSeeCurrentUrlEquals('/dashboard');
    }
}