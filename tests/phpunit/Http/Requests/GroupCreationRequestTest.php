<?php namespace Http\Requests;

use BibleBowl\Http\Requests\GroupCreationRequest;
use Input;
use Mockery;
use Laravel\Socialite\Two\User as ThirdPartyUser;

class GroupCreationRequestTest extends \TestCase
{
    /** @var GroupCreationRequest */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new GroupCreationRequest();
    }

    /**
     * @test
     */
    public function requiresExistingAddressByDefault()
    {
        $this->request = new GroupCreationRequest([], [
            'user_owned_address' => 1
        ]);

        $rules = $this->request->rules();

        $this->assertArrayHasKey('address_id', $rules);

        // not requiring a new address
        $this->assertArrayNotHasKey('address_one', $rules);
        $this->assertArrayNotHasKey('city', $rules);
        $this->assertArrayNotHasKey('state', $rules);
        $this->assertArrayNotHasKey('zip_code', $rules);
    }

    /**
     * @test
     */
    public function requiresNewAddress()
    {
        $rules = $this->request->rules();

        //not requiring an existing address
        $this->assertArrayNotHasKey('address_id', $rules);

        $this->assertArrayHasKey('address_one', $rules);
        $this->assertArrayHasKey('city', $rules);
        $this->assertArrayHasKey('state', $rules);
        $this->assertArrayHasKey('zip_code', $rules);
    }

}