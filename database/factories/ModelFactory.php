<?php

use BibleBowl\User;
use BibleBowl\Player;
use BibleBowl\Role;

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

$factory->define(User::class, function ($faker) {
    return [
        'status'            => User::STATUS_CONFIRMED,
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->email,
        'password'          => str_random(10),
        'remember_token'    => str_random(10),
    ];
});

$factory->define(Player::class, function ($faker) {
    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'gender'            => (rand(0, 1)) ? 'M' : 'F',
        'birthday'          => $faker->dateTimeBetween('-18 years', '-9 years')->format('m/d/Y')

    ];
});

/**
 * @return User
 */
function seedGuardian($email = null)
{
    $attrs = [];
    if (!is_null($email)) {
        $attrs['email'] = $email;
    }
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