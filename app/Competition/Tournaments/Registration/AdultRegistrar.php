<?php

namespace BibleBowl\Competition\Tournaments\Registration;

use BibleBowl\Address;
use BibleBowl\Group;
use BibleBowl\Spectator;
use BibleBowl\Tournament;
use BibleBowl\User;

class AdultRegistrar
{
    public function register(
        Tournament $tournament,
        array $attributes = null,
        User $user = null,
        Group $group = null
    ) : Spectator {
        $adult = app(Spectator::class, [[
            'tournament_id' => $tournament->id,
            'group_id'      => $group == null ? null : $group->id,
            'shirt_size'    => $attributes['shirt_size'],
        ]]);

        if ($user != null) {
            $adult->user_id = $user->id;
        } else {

            // attempt to look up the user by email address
            if (isset($attributes['email'])) {
                $user = User::where('email', $attributes['email'])->first();
                if ($user != null && $user->exists()) {
                    $adult->user_id = $user->id;
                }
            }

            if (isset($attributes['address_one'])) {
                $adult->address_id = Address::create([
                    'address_one'   => $attributes['address_one'],
                    'address_two'   => $attributes['address_two'],
                    'zip_code'      => $attributes['zip_code'],
                ])->id;
            }
        }

        // fields are set by head coaches when they register for adults
        if (isset($attributes['first_name'])) {
            $adult->first_name = $attributes['first_name'];
        }
        if (isset($attributes['last_name'])) {
            $adult->last_name = $attributes['last_name'];
        }
        if (isset($attributes['email'])) {
            $adult->email = $attributes['email'];
        }
        if (isset($attributes['gender'])) {
            $adult->gender = $attributes['gender'];
        }

        $adult->save();

        return $adult;
    }
}