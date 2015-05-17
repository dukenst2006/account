<?php namespace Lib;

use AcceptanceTester;

class AuthHelper
{
    public static function logout(AcceptanceTester $I)
    {
        $I->click('#user-options');
        $I->click('Log Out');
    }
}