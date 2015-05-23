<?php

use Biblebowl\User;
use Lib\AuthHelper;

class AuthCest
{
    protected $firstName = 'Bill';
    protected $lastName = 'Johnson';
    protected $email = null;
    protected $password = 'asdfasdf';

//    public function logout(AcceptanceTester $I)
//    {
//        $I->amOnPage('/dashboard');
//
//        AuthHelper::logout($I);
//
//        $I->canSeeCurrentUrlEquals('/login');
//    }

    public function loginRequiresEmailConfirmation(AcceptanceTester $I)
    {
        AuthHelper::login($I, AcceptanceTestingSeeder::UNCONFIRMED_USER_EMAIL);
        $I->see("Your email address is not yet confirmed.");

        $user = User::where('email', AcceptanceTestingSeeder::UNCONFIRMED_USER_EMAIL)->first();
        $I->amOnPage('/register/confirm/'.$user->guid);

        AuthHelper::login($I, 'unconfirmed-'.AcceptanceTestingSeeder::USER_EMAIL);

        $I->canSeeInCurrentUrl('/dashboard');

        $user->save(); //set back to unconfirmed
    }

//    public function registerNewAccount(AcceptanceTester $I)
//    {
//        # Seed email
//        $this->email = 'actest'.time().'@testerson.com';
//
//        $I->amOnPage('/login');
//
//        $I->click('register a new account');
//
//        $I->fillField('email', $this->email);
//        $I->fillField('password', $this->password);
//        $I->fillField('password_confirmation', $this->password);
//
//        $I->click('Register');
//
//        $I->canSeeCurrentUrlEquals('/dashboard');
//
//        $I->see($this->firstName.' '.$this->lastName);
//
//        //switch currently logged in user back to the default
//        AuthHelper::logout($I);
//        AuthHelper::login($I);
//    }
}