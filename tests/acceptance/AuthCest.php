<?php

class AuthCest
{
    protected $firstName = 'Bill';
    protected $lastName = 'Johnson';
    protected $email = null;
    protected $password = 'asdfasdf';

    public function logout(AcceptanceTester $I)
    {
        $I->amOnPage('/dashboard');

        \Lib\AuthHelper::logout($I);

        $I->canSeeCurrentUrlEquals('/login');
    }

    public function registerNewAccount(AcceptanceTester $I)
    {
        # Seed email
        $this->email = 'actest'.time().'@testerson.com';

        $I->amOnPage('/login');

        $I->click('register a new account');

        $I->fillField('first_name', $this->firstName);
        $I->fillField('last_name', $this->lastName);
        $I->fillField('email', $this->email);
        $I->fillField('password', $this->password);
        $I->fillField('password_confirmation', $this->password);

        $I->click('Register');

        $I->canSeeCurrentUrlEquals('/dashboard');

        $I->see($this->firstName.' '.$this->lastName);

        //switch currently logged in user back to the default
        \Lib\AuthHelper::logout($I);
        \Lib\AuthHelper::login($I);
    }
}