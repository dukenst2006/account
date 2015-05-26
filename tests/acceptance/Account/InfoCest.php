<?php

class InfoCest
{
    public function editAccountDetails(AcceptanceTester $I)
    {
        $I->amOnPage('/account/edit');

        $I->fillField('phone', '1234567890');

        $I->click('Save');
        $I->see('Your changes were saved');
    }
}