<?php namespace Lib;

use AcceptanceTestingSeeder;
use AcceptanceTester;

class AuthHelper
{
    public static function logout(AcceptanceTester $I)
    {
        $I->click('#user-options');
        $I->click('Log Out');
    }

    public static function login(AcceptanceTester $I)
    {
        $I->amOnPage('/login');

        $I->fillField('email', AcceptanceTestingSeeder::USER_EMAIL);
        $I->fillField('password', AcceptanceTestingSeeder::USER_PASSWORD);

        $I->click('Login');
    }
}