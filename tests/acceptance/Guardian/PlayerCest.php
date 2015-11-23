<?php

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
        $I->fillField('birthday', '05/14/2001');
        $I->click('Save');

        $I->see('The first name field is required.');
        $I->fillField('first_name', $this->firstName);
        $I->click('Save');

        $I->see($this->firstName.' '.$this->lastName.' has been added');

        $I->canSeeCurrentUrlEquals('/dashboard');

        $I->see($this->firstName.' '.$this->lastName);

        //test editing
        $I->click('[ Edit ]');
        $this->lastName = $this->lastName.time();
        $I->fillField('last_name', $this->lastName);

        $I->click('Save');
        $I->see('Your changes were saved');
        $I->see($this->lastName);
    }
}