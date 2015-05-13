<?php
use \AcceptanceTester;
use BibleBowl\User;

class AuthCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function tryToLogin(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('email', 'tester@testerson.com');
        $I->fillField('password', 'asdf');

        $I->click('Login');

        $I->canSeeCurrentUrlEquals('/home');
    }

    public function tryToRegister(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->click('Sign up Now!');

        $I->fillField('first_name', 'Bill');
        $I->fillField('last_name', 'Johnson');
        $email = 'actest'.time().'@testerson.com';
        $I->fillField('email', $email);
        $I->fillField('password', 'asdfasdf');
        $I->fillField('password_confirmation', 'asdfasdf');

        $I->click('Register');

        $I->canSeeCurrentUrlEquals('/home');

        //cleanup
        User::where([
            'email' => $email
        ])->delete();
    }
}