<?php

use BibleBowl\Address;

class AddressTest extends TestCase
{
    protected $name;
    protected $addressOne = '123 My Street';
    protected $addressTwo = 'Apt 6';
    protected $zipCode = 40241;

    use \Helpers\ActingAsGuardian;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsGuardian();
    }

    /**
     * @test
     */
    public function canCreateAddress()
    {
        $this->name = 'Test '.rand(1, 400000);

        $this
            ->visit('/account/address')
            ->click('New Address')
            ->type($this->addressOne, 'address_one')
            ->type($this->addressOne, 'address_two')
            ->type($this->zipCode, 'zip_code')
            ->press('Save')
            ->see('The name field is required.')

            ->type($this->name, 'name')
            ->press('Save')
            ->see($this->name);
    }

    /**
     * @test
     */
    public function canEditAddress()
    {
        $address = $this->guardian()->addresses()->orderBy('created_at', 'DESC')->first();
        $name = 'Test '.rand(1, 400000);
        $this
            ->visit('/account/address')
            ->click('#edit-'.$address->id)
            ->type($name, 'name')
            ->press('Save')
            ->see('Your changes were saved');

        // can't figure out why the address is showing up cached
        // in the browser... so we'll check the DB directly
        $this->assertEquals($name, Address::findOrFail($address->id)->name);
    }

    /**
     * @test
     */
    public function canDeleteAddress()
    {
        $address = $this->guardian()->addresses()->orderBy('created_at', 'DESC')->first();

        // Because of the dropdown menu's functionality for delete being
        // in javascript we'll have to simulate the request
        $address->delete();

        // bypassing caching, see above comments
        $this->assertEquals(0, Address::where('id', $address->id)->count());
    }

}