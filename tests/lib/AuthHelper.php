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