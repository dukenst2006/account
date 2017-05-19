<?php

namespace App\Competition\Tournaments\Spectators;

use App\Address;
use App\Group;
use App\Minor;
use App\Spectator;
use App\Tournament;
use App\User;

class Registrar
{
    public function register(
        Tournament $tournament,
        array $attributes = null,
        User $user = null,
        Group $group = null,
        User $registeredBy = null
    ) : Spectator {
        $adult = new Spectator([
            'tournament_id' => $tournament->id,
            'group_id'      => $group == null ? null : $group->id,
        ]);

        if ($tournament->settings->shouldCollectShirtSizes()) {
            $adult->shirt_size = $attributes['shirt_size'];
        }

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
                $address = new Address([
                    'address_one'   => $attributes['address_one'],
                    'address_two'   => $attributes['address_two'],
                    'zip_code'      => $attributes['zip_code'],
                ]);
                $address->save();
                $adult->address_id = $address->id;
            }
        }

        // record if registered by another user
        if ($registeredBy != null) {
            $adult->registered_by = $registeredBy->id;
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
        if (isset($attributes['phone'])) {
            $adult->phone = $attributes['phone'];
        }

        // spouse data
        if (isset($attributes['spouse_first_name']) && !empty($attributes['spouse_first_name'])) {
            $adult->spouse_first_name = trim(str_replace($adult->last_name, '', $attributes['spouse_first_name']));
            $adult->spouse_gender = $attributes['spouse_gender'];
            if ($tournament->settings->shouldCollectShirtSizes()) {
                $adult->spouse_shirt_size = $attributes['spouse_shirt_size'];
            }
        }

        $adult->save();

        // attach minors
        if (isset($attributes['minor'])) {
            $minors = [];
            foreach ($attributes['minor'] as $minor) {
                if (strlen($minor['first_name']) > 0) {
                    $minorData = [
                                           // remove last name, people seem to provide this
                        'name'          => trim(str_replace($adult->last_name, '', $minor['first_name'])),
                        'age'           => $minor['age'],
                        'gender'        => $minor['gender'],
                    ];

                    if ($tournament->settings->shouldCollectShirtSizes()) {
                        $minorData['shirt_size'] = $minor['shirt_size'];
                    }

                    $minors[] = new Minor($minorData);
                }
            }

            if (count($minors) > 0) {
                $adult->minors()->saveMany($minors);
            }
        }

        return $adult;
    }
}
