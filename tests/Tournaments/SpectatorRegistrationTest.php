<?php

use BibleBowl\Group;
use BibleBowl\ParticipantType;
use BibleBowl\Spectator;
use BibleBowl\Tournament;
use Helpers\ActingAsGuardian;
use Helpers\SimulatesTransactions;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpectatorRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use ActingAsGuardian;
    use SimulatesTransactions;

    /**
     * @test
     */
    public function canRegisterWithoutGroupAndWithFees()
    {
        $this->setupAsGuardian();
        $this->simulateTransaction();

        $shirtSize = 'XL';
        $tournament = Tournament::firstOrFail();
        $this
            ->visit('/tournaments/'.$tournament->slug.'/registration/spectator')
            ->select($shirtSize, 'shirt_size')
            ->press('Continue')
            ->seePageIs('/cart')
            ->see('Adult Tournament Registration')
            ->press('Submit')
            ->see('Your registration is complete');

        $spectator = Spectator::orderBy('id', 'desc')->first();
        $this->assertEquals($shirtSize, $spectator->shirt_size);

        // defaults to no group selected
        $this->assertNull($spectator->group_id);

        // we use the receipt_id to determine if payment has been made
        $this->assertGreaterThan(0, $spectator->receipt_id);
    }

    /**
     * @test
     */
    public function canRegisterWithGroupAndWithoutFees()
    {
        $this->setupAsGuardian();
        $this->simulateTransaction();

        $tournament = Tournament::firstOrFail();

        // Remove fees for quizmasters
        $tournament->participantFees()
            ->where('participant_type_id', ParticipantType::ADULT)
            ->update([
                'earlybird_fee' => 0,
                'fee'           => 0,
        ]);

        $group = Group::byProgram($tournament->program_id)->first();
        $this
            ->visit('/tournaments/'.$tournament->slug.'/registration/spectator')
            ->select($group->id, 'group_id')
            ->press('Submit')
            ->see('Your registration is complete');

        $spectator = Spectator::orderBy('id', 'desc')->first();

        $this->assertEquals($group->id, $spectator->group_id);

        // no payment was made, so we shouldn't have a receipt
        $this->assertNull($spectator->receipt_id);
    }

    /**
     * @test
     */
    public function canRegisterAFamilyAsGuestWithFees()
    {
        $this->simulateTransaction();

        $shirtSize = 'XL';
        $tournament = Tournament::firstOrFail();
        $playerFirstName = 'Mark';
        $firstName = 'John';
        $lastName = 'Smith';
        $email = 'testuser'.time().'@example.com';
        $street = '123 Test Street';
        $spouseFirstName = 'apples';
        $this
            ->visit('/tournaments/'.$tournament->slug.'/registration/spectator')
            ->type($firstName, 'first_name')
            ->type($lastName, 'last_name')
            ->type($email, 'email')

            // assert address is required since we're using dynamic validation rules
            ->press('Continue')
            ->see('The street address field is required')
            ->see('The zip code field is required')

            ->type($street, 'address_one')
            ->type('12345', 'zip_code')
            ->select($shirtSize, 'shirt_size')
            ->type($spouseFirstName, 'spouse_first_name')
            ->type($playerFirstName, 'minor[1][first_name]')
            ->press('Continue')
            ->seePageIs('/cart')
            ->see('Family Tournament Registration')
            ->press('Submit')
            ->see('Your registration is complete');

        $spectator = Spectator::orderBy('id', 'desc')->first();
        $this->assertEquals($shirtSize, $spectator->shirt_size);

        // defaults to no group selected
        $this->assertNull($spectator->group_id);
        $this->assertEquals($firstName, $spectator->first_name);
        $this->assertEquals($lastName, $spectator->last_name);
        $this->assertEquals($email, $spectator->email);
        $this->assertEquals($spouseFirstName, $spectator->spouse_first_name);

        // we use the receipt_id to determine if payment has been made
        $this->assertGreaterThan(0, $spectator->receipt_id);

        // verify address is populated
        $this->assertEquals($street, $spectator->address->address_one);

        // assert minor is there
        $this->assertEquals($playerFirstName, $spectator->minors()->first()->name);
    }
}
