<?php

class GuardianSeasonRegistrationCest
{
    protected $firstName = 'Lucy';
    protected $lastName = 'Tharn';

    public function register(AcceptanceTester $I)
    {
        $I->amOnPage('/seasons/register');

        $I->click('Register');

        $I->see('Your players have been registered!');
    }
}