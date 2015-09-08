<?php

use Lib\AuthHelper;
use Lib\SeasonRegistrationHelper;

class GuardianPlayerRegistrationCest
{
    protected $firstName = 'Lucy';
    protected $lastName = 'Tharn';

    public function registerPlayerWithNearbyGroup(AcceptanceTester $I)
    {
        $playerName = AcceptanceTestingSeeder::GUARDIAN_PLAYER_A_FULL_NAME;

        AuthHelper::logout($I);
        AuthHelper::login($I, AcceptanceTestingSeeder::GUARDIAN_EMAIL, AcceptanceTestingSeeder::GUARDIAN_PASSWORD);
        $I->amOnPage('/dashboard');

        $I->click(SeasonRegistrationHelper::dashboardRegistrationLink($playerName));
        $I->see('Groups Nearby');

        //register with Southeast
        $groupName = 'Southeast Christian Church';
        $I->click(SeasonRegistrationHelper::selectThisGroupLink($groupName));
        $I->see($groupName);
        $I->see('Register for '.$this->getCurrentSeasonLabel());

        // verify we're requiring a player to be selected
        // uncheck players since they're checked by default
        $I->wait(1); //allows JS to initialize for checkboxes
        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_A_FULL_NAME);
        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_B_FULL_NAME);
        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_C_FULL_NAME);

        $I->click('Register');
        $I->see('You must select a player to register');
        $I->makeScreenshot('after-uncheck');
        $this->selectPlayer($I, $playerName, 11, 'XL');
        $I->click('Register');
        $I->see('Your player(s) have been registered!');
    }

    public function registerPlayerWithSearchingGroup(AcceptanceTester $I)
    {
        $playerName = AcceptanceTestingSeeder::GUARDIAN_PLAYER_B_FULL_NAME;

        $I->click(SeasonRegistrationHelper::dashboardRegistrationLink($playerName));

        $groupName = 'Mount Pleasant Christian Church';
        $I->fillField('q', 'Mount');
        $I->pressKey('input[name="q"]',WebDriverKeys::ENTER);
        $I->click(SeasonRegistrationHelper::selectThisGroupLink($groupName));
        $I->see('Register for '.$this->getCurrentSeasonLabel());

        // don't see players already registered (in previous test)
        $I->dontSee(AcceptanceTestingSeeder::GUARDIAN_PLAYER_A_FULL_NAME);

        $I->wait(1); //allows JS to initialize for checkboxes
        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_B_FULL_NAME);
        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_C_FULL_NAME);
        $this->selectPlayer($I, $playerName, 10, 'M');
        $I->click('Register');
        $I->see('Your player(s) have been registered!');
    }

    public function registerPlayerWithNoGroup(AcceptanceTester $I)
    {
        $playerName = AcceptanceTestingSeeder::GUARDIAN_PLAYER_C_FULL_NAME;

        $I->click(SeasonRegistrationHelper::dashboardRegistrationLink($playerName));
        $I->click('Register');
        $I->see('Register for '.$this->getCurrentSeasonLabel());

        // don't see players already registered (in previous tests)
        $I->dontSee(AcceptanceTestingSeeder::GUARDIAN_PLAYER_A_FULL_NAME);
        $I->dontSee(AcceptanceTestingSeeder::GUARDIAN_PLAYER_B_FULL_NAME);

        $I->click('Register');
        $I->see('Your player(s) have been registered!');
    }

    public function joinPlayerWithNearbyGroup(AcceptanceTester $I)
    {
        $playerName = AcceptanceTestingSeeder::GUARDIAN_PLAYER_C_FULL_NAME;

        $I->amOnPage('/dashboard');

        $I->click(SeasonRegistrationHelper::dashboardJoinGroupLink($playerName));
        $I->see('Groups Nearby');

        //register with Southeast
        $groupName = 'Southeast Christian Church';
        $I->click(SeasonRegistrationHelper::joinGroupLink($groupName));

        $I->see($groupName);
        $I->see('Join for '.$this->getCurrentSeasonLabel());

        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_C_FULL_NAME);
        $I->click('Join');
        $I->see('You must select at least one player');

        $this->selectPlayer($I, AcceptanceTestingSeeder::GUARDIAN_PLAYER_C_FULL_NAME);
        $I->click('Join');
        $I->see('Your player(s) have joined a group!');
    }

    /**
     * Select a player during the registration process
     */
    private function selectPlayer(AcceptanceTester $I, $fullName, $grade = null, $shirtSize = null)
    {
        $playerRow = SeasonRegistrationHelper::playerRow($fullName);
        $I->click($playerRow.'/td[1]/div/label'); # check the player checkbox

        if (!is_null($grade)) {
            $I->selectOption($playerRow.'/td[3]/select', $grade);
        }

        if (!is_null($shirtSize)) {
            $I->selectOption($playerRow.'/td[4]/select', $shirtSize);
        }
    }

    private function getCurrentSeasonLabel()
    {
        $now = \Carbon\Carbon::now();
        return $now->format("Y-").($now->addYear()->format("y")).' Season';
    }
}