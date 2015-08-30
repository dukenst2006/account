<?php

use Faker\Generator;
use BibleBowl\User;
use BibleBowl\Player;
use BibleBowl\Role;
use BibleBowl\Address;

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

/**
 * @return User
 */
function seedGuardian($attrs = [], $addressAttrs = [])
{
    $address = factory(Address::class)->create($addressAttrs);
    $attrs['primary_address_id'] = $address->id;

    $user = factory(User::class)->create($attrs);
    $user->attachRole(Role::GUARDIAN_ID);
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