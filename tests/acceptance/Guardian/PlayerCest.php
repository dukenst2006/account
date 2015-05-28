<?php

use Lib\AuthHelper;

class GuardianPlayerCest
{
    protected $firstName = 'Lucy';
    protected $lastName = 'Tharn';

    public function managePlayer(AcceptanceTester $I)
    {
        $I->amOnPage('/dashboard');

        $I->click('Add my child(ren)');

        $this->firstName .= time();

        $I->fillField('last_name', $this->lastName);
        $I->selectOption('shirt_size', 'XL');
        $I->fillField('birthday', '05/14/2001');
        $I->click('Save');

        $I->see('The first name field is required.');
        $I->fillField('first_name', $this->firstName);
        $I->click('Save');

        $I->see($this->firstName.' '.$this->lastName.' has been added');

        $I->canSeeCurrentUrlEquals('/dashboard');

        $I->see($this->firstName.' '.$this->lastName);
//
//        //test editing
//        $I->click('(//a[contains(@class,"fa-edit")])[2]');
//        $this->lastName = $this->lastName.time();
//        $I->fillField('last_name', $this->lastName);
//
//        $I->click('Save');
//        $I->see('Your changes were saved');
//
//        //test deleting
//        $I->click('(//a[contains(@class,"fa-trash-o")])[2]');
//
//        $I->see('Your '.$this->name.' address has been deleted');
//        $I->dontSee($this->name, 'h4');
    }
}