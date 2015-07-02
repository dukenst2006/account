<?php

use Lib\AuthHelper;

class AccountAddressCest
{
    protected $name;
    protected $addressOne = '123 My Street';
    protected $addressTwo = 'Apt 6';
    protected $city = 'Louisville';
    protected $state = 'KY';
    protected $zipCode = 40241;

    public function manageAddresses(AcceptanceTester $I)
    {
        //required since this is the first test that runs
        AuthHelper::login($I);

        $I->amOnPage('/account/address');

        $I->click('New Address');

        $this->name = 'Test '.time();

        // skip name so we see a validation error
        $I->fillField('address_one', $this->addressOne);
        $I->fillField('address_two', $this->addressTwo);
        $I->fillField('city', $this->city);
        $I->selectOption('state', $this->state);
        $I->fillField('zip_code', $this->zipCode);

        $I->click('Save');
        $I->see('The name field is required.');

        $I->fillField('name', $this->name);

        $I->click('Save');
        $I->see('Your '.$this->name.' address has been created');

        $I->canSeeCurrentUrlEquals('/account/address');

        $I->see($this->name);

        //test editing
        $I->click('(//a[contains(@class,"fa-edit")])[2]');
        $this->name = $this->name.time();
        $I->fillField('name', $this->name);

        $I->click('Save');
        $I->see('Your changes were saved');

        //test deleting
        $I->click('(//a[contains(@class,"fa-trash-o")])[2]');

        $I->see('Your '.$this->name.' address has been deleted');
        $I->dontSee($this->name, 'h4');
    }
}