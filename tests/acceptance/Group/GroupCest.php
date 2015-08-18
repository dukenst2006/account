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

        $I->canSeeCurrentUrlEquals('/dashboard');

        // verify name is the default group in the session
        $I->see($this->name);

        //test editing
        $I->click('(//div[contains(@class,"groupname")]/a[contains(@class,"fa-edit")])');
        $this->name = $this->name.time();
        $I->fillField('name', $this->name);

        $I->click('Save');
        $I->see('Your changes were saved');
    }
}