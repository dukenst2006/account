<?php

use BibleBowl\Address;
use Codeception\Util\Locator;

class AccountAddressCest
{
    protected $name;
    protected $firstName = 'Bill';
    protected $lastName = 'Johnson';
    protected $addressOne = '123 My Street';
    protected $addressTwo = 'Apt 6';
    protected $city = 'Louisville';
    protected $state = 'KY';
    protected $zipCode = 40241;

    public function createNewAddress(AcceptanceTester $I)
    {
        \Lib\AuthHelper::login($I);

        $I->amOnPage('/account/address');

        $I->click('New Address');

        $this->name = 'Test '.time();

        $I->fillField('name', $this->name);
        // skip first name so we see a validation error
        $I->fillField('last_name', $this->lastName);
        $I->fillField('address_one', $this->addressOne);
        $I->fillField('address_two', $this->addressTwo);
        $I->fillField('city', $this->city);
        $I->selectOption('state', $this->state);
        $I->fillField('zip_code', $this->zipCode);

        $I->click('Save');
        $I->see('The first name field is required.');

        $I->fillField('first_name', $this->firstName);

        $I->click('Save');
        $I->see('Your '.$this->name.' address has been created');

        $I->canSeeCurrentUrlEquals('/account/address');

        $I->see($this->name);
        $I->see($this->firstName.' '.$this->lastName);

        //test editing
        $I->click('(//a[contains(@class,"fa-edit")])[2]');
        $this->lastName = $this->lastName.time();
        $I->fillField('last_name', $this->lastName);

        $I->click('Save');
        $I->see('Your changes were saved');

        //test deleting
        $I->click('(//a[contains(@class,"fa-trash-o")])[2]');

        $I->see('Your '.$this->name.' address has been deleted');
        $I->dontSee($this->name, 'h4');
    }
}