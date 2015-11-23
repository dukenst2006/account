<?php namespace Lib;

use AcceptanceTester;
use AcceptanceTestingSeeder;

class AuthHelper
{
    public static function isLoggedIn(AcceptanceTester $I)
    {
        $I->see('#user-options');
    }

    public static function logout(AcceptanceTester $I)
    {
        $I->click('#user-options');
        $I->click('Log Out');
    }

    public static function login(
        AcceptanceTester $I,
        $email = AcceptanceTestingSeeder::USER_EMAIL,
        $password = AcceptanceTestingSeeder::USER_PASSWORD
    ) {
        $I->amOnPage('/login');

        $I->fillField('email', $email);
        $I->fillField('password', $password);

        $I->click('Login');
    }
}