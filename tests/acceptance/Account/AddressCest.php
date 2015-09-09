<?php

use Lib\AuthHelper;

class AccountAddressCest
{
    protected $name;
    protected $addressOne = '123 My Street';
    protected $addressTwo = 'Apt 6';
    protected $zipCode = 40241;

    public function manageAddresses(AcceptanceTester $I)
    {
        //required since this is the first test that runs
        AuthHelper::login($I);

        $I->amOnPage('/account/address');

        $I->click('New Address');

        $this->name = 'Test '.rand(1, 400000);

        // skip name so we see a validation error
        $I->fillField('address_one', $this->addressOne);
        $I->fillField('address_two', $this->addressTwo);
        $I->fillField('zip_code', $this->zipCode);

        $I->click('Save');
        $I->see('The name field is required.');

        $I->fillField('name', $this->name);

        $I->click('Save');
        $I->see('Your '.$this->name.' address has been created');

        $I->canSeeCurrentUrlEquals('/account/address');

        $I->see($this->name);

        //test editing
        $I->click('.address-card:nth-of-type(2) .address-ops');
        $I->click('.address-card:nth-of-type(2) .control-edit');
        $this->name = $this->name.time();
        $I->fillField('name', $this->name);

        $I->click('Save');
        $I->see('Your changes were saved');

        //test deleting
        $I->click('.address-card:nth-of-type(2) .address-ops');
        $I->click('.address-card:nth-of-type(2) .control-delete');

        $I->see('Your '.$this->name.' address has been deleted');
        $I->dontSee($this->name, 'h4');
    }
}