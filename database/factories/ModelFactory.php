<?php

use Faker\Generator;
use BibleBowl\Program;
use BibleBowl\User;
use BibleBowl\Player;
use BibleBowl\Role;
use BibleBowl\Address;
use BibleBowl\Groups\GroupCreator;
use BibleBowl\Tournament;
use BibleBowl\Season;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Generator $faker) {
    return [
        'status'            => User::STATUS_CONFIRMED,
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->email,
        'phone'             => $faker->phoneNumber,
        'password'          => str_random(10),
        'remember_token'    => str_random(10),
    ];
});

$factory->define(Player::class, function (Generator $faker) {
    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'gender'            => (rand(0, 1)) ? 'M' : 'F',
        'birthday'          => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y')
    ];
});

$factory->define(Address::class, function (Generator $faker) {
    return [
        'name'              => 'Home',
        'address_one'	    => $faker->buildingNumber . ' ' . $faker->streetName . ' ' . $faker->streetSuffix,
        'address_two'	    => (rand(0, 5)) ? $faker->secondaryAddress : null, // randomized
        'latitude'          => $faker->latitude,
        'longitude'         => $faker->longitude,
        'city'			    => $faker->city,
        'state'			    => $faker->stateAbbr,
        'zip_code'		    => $faker->postcode
    ];
});

$factory->define(Tournament::class, function (Generator $faker) {
    return [
        'name'                  => $faker->word,
        'season_id'	            => Season::current()->id,
        'start'	                => Carbon::now()->addMonth(1),
        'end'                   => Carbon::now()->addDays(14),
        'registration_start'    => Carbon::now()->subMonth(1),
        'registration_end'		=> Carbon::now()->subDays(14),
        'creator_id'			=> User::where('email', DatabaseSeeder::DIRECTOR_EMAIL)->first()->id
    ];
});

/**
 * @return User
 */
function seedGuardian($attrs = [], $addressAttrs = [])
{
    $address = factory(Address::class)->create($addressAttrs);
    $attrs['primary_address_id'] = $address->id;

    $user = factory(User::class)->create($attrs);
    $address->user_id = $user->id;
    $address->save();

    $role = Role::findOrFail(Role::GUARDIAN_ID);
    $user->attachRole($role);
    return $user;
}

/**
 * @return \BibleBowl\Player
 */
function seedPlayer(User $user)
{
    $player = factory(Player::class)->create([
        'guardian_id' => $user->id
    ]);
    return $player;
}

/**
 * @return \BibleBowl\Player
 */
function seedGroup(User $headCoach)
{
    $group = app(GroupCreator::class);
    return $group->create($headCoach, [
        'name'                  => 'Group '.microtime(),
        'program_id'            => Program::TEEN,
        'address_id'            => $headCoach->primary_address_id,
        'meeting_location_id'   => $headCoach->primary_address_id
    ]);
}