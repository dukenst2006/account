<?php

use BibleBowl\Address;
use BibleBowl\Players\PlayerCreator;
use BibleBowl\Program;
use BibleBowl\Receipt;
use BibleBowl\Role;
use BibleBowl\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class AcceptanceTestingSeeder extends Seeder
{
    const USER_FIRST_NAME = 'Joe';
    const USER_LAST_NAME = 'Walters';
    const USER_EMAIL = 'joe.walters@example.com';
    const USER_PASSWORD = 'changeme';

    const GUARDIAN_EMAIL = 'testUser+guardian@gmail.com';
    const GUARDIAN_PASSWORD = 'changeme';

    const GUARDIAN_PLAYER_A_FULL_NAME = 'John Watson';
    const GUARDIAN_PLAYER_B_FULL_NAME = 'Emily Smith';
    const GUARDIAN_PLAYER_C_FULL_NAME = 'Alex Johnson';

    const UNCONFIRMED_USER_EMAIL = 'unconfirmed-joe.walters@example.com';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $homeAddress = Address::create([
            'name'             => 'Home',
            'address_one'      => '123 Acceptance Test Seeder Street',
            'address_two'      => null,
            'city'             => 'Louisville',
            'state'            => 'KY',
            'zip_code'         => '40241',
        ]);

        $TestUser = User::create([
          'status'                => User::STATUS_CONFIRMED,
          'first_name'            => self::USER_FIRST_NAME,
          'last_name'             => self::USER_LAST_NAME,
          'email'                 => self::USER_EMAIL,
          'password'              => bcrypt(self::USER_PASSWORD),
          'primary_address_id'    => $homeAddress->id,
        ]);
        $TestUser->addresses()->save($homeAddress);

        // used for asserting you can't login without being confirmed
        $unconfirmedAddress = factory(Address::class)->create();
        User::create([
            'first_name'               => self::USER_FIRST_NAME.'-unconfirmed',
            'last_name'                => self::USER_LAST_NAME,
            'email'                    => self::UNCONFIRMED_USER_EMAIL,
            'password'                 => bcrypt(self::USER_PASSWORD),
            'primary_address_id'       => $unconfirmedAddress->id,
        ]);

        $this->seedGuardian();
        $this->seedReceipts();

        if (app()->environment('local')) {
            $this->updateMailchimpIds();
        }
    }

    /**
     * This Guardian gains the role of "Guardian" when acceptance tests run.
     */
    private function seedGuardian()
    {
        $addresses = ['Home', 'Work'];
        $testAddresses = [];

        foreach ($addresses as $key => $name) {
            $testAddresses[] = factory(Address::class)->create([
                'name'             => $name,
                'latitude'         => '38.2515659',
                'longitude'        => '-85.615241',
                'city'             => 'Louisville',
                'state'            => 'KY',
                'zip_code'         => '40241',
            ]);
        }

        $testGuardian = User::create([
          'status'                    => User::STATUS_CONFIRMED,
          'first_name'                => 'Test',
          'last_name'                 => 'Guardian',
          'email'                     => self::GUARDIAN_EMAIL,
          'password'                  => bcrypt(self::GUARDIAN_PASSWORD),
          'primary_address_id'        => $testAddresses[0]->id,
        ]);
        $testGuardian->addresses()->saveMany($testAddresses);

        $playerCreator = App::make(PlayerCreator::class);
        $playerCreator->create($testGuardian, [
          'first_name'    => 'John',
          'last_name'     => 'Watson',
          'gender'        => 'M',
          'birthday'      => '2/24/1988',
        ]);
        $testGuardian = User::find($testGuardian->id);
        $playerCreator->create($testGuardian, [
          'first_name'    => 'Emily',
          'last_name'     => 'Smith',
          'gender'        => 'F',
          'birthday'      => '2/24/1986',
        ]);
        $playerCreator->create($testGuardian, [
          'first_name'    => 'Alex',
          'last_name'     => 'Johnson',
          'gender'        => 'M',
          'birthday'      => '6/14/1987',
        ]);
    }

    /**
     * Update the mailchimp ids so they match the staging list instead of production.
     */
    private function updateMailchimpIds()
    {
        Role::where('name', Role::LEAGUE_COORDINATOR)->update([
            'mailchimp_interest_id' => 'da431848e5',
        ]);
        Role::where('name', Role::HEAD_COACH)->update([
            'mailchimp_interest_id' => '8eb76f09f0',
        ]);
        Role::where('name', Role::COACH)->update([
            'mailchimp_interest_id' => 'd531b08cdb',
        ]);
        Role::where('name', Role::QUIZMASTER)->update([
            'mailchimp_interest_id' => 'bddc8cb120',
        ]);
        Role::where('name', Role::GUARDIAN)->update([
            'mailchimp_interest_id' => 'f29d2ce1ef',
        ]);
    }

    private function seedReceipts()
    {
        $teen = Program::findOrFail(Program::TEEN);
        $receipt = Receipt::create([
            'total'                     => $teen->registration_fee * 3,
            'payment_reference_number'  => uniqid(),
            'user_id'                   => DatabaseSeeder::$guardian->id,
            'address_id'                => DatabaseSeeder::$guardian->primary_address_id,
        ]);

        $receipt->items()->create([
            'sku'           => $teen->sku,
            'description'   => $teen->name.' Seasonal Registration',
            'quantity'      => '3',
            'price'         => $teen->registration_fee,
        ]);

        $createdAt = Carbon::now()->subMonth();
        $beginner = Program::findOrFail(Program::BEGINNER);
        $receipt = Receipt::create([
            'total'                     => $beginner->registration_fee * 3,
            'payment_reference_number'  => uniqid(),
            'user_id'                   => DatabaseSeeder::$guardian->id,
            'address_id'                => DatabaseSeeder::$guardian->primary_address_id,
            'created_at'                => $createdAt,
        ]);

        $receipt->items()->create([
            'sku'           => $beginner->sku,
            'description'   => $beginner->name.' Seasonal Registration',
            'quantity'      => '3',
            'price'         => $beginner->registration_fee,
            'created_at'    => $createdAt,
        ]);
    }
}
