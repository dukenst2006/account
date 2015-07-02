<?php

use Lib\AuthHelper;

class GroupCest
{
    protected $name;

    public function manageGroups(AcceptanceTester $I)
    {
        $I->amOnPage('/dashboard');

        $I->click('Add my group');

        $I->click('Save');
        $I->see('The name field is required.');

        $this->name = 'Group '.time();
        $I->fillField('name', $this->name);
        $I->click('Save');
        $I->see($this->name.' has been created');
//
//        $I->canSeeCurrentUrlEquals('/dashboard');
//
//        $I->see($this->name);
//
//        //test editing
//        $I->click('(//a[contains(@class,"fa-edit")])[2]');
//        $this->name = $this->name.time();
//        $I->fillField('name', $this->name);
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