<?php
use \AcceptanceTester;

class AuthCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('email', 'tester@testerson.com');
        $I->fillField('password', 'asdf');

        $I->click('Login');

        $I->canSeeCurrentUrlEquals('/home');
    }
}