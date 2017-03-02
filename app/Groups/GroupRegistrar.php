<?php

namespace App\Groups;

use App\Group;
use App\Role;
use App\Season;
use App\Seasons\GroupRegistration;
use App\User;
use DB;
use Mail;

class GroupRegistrar
{
    public function register(Season $season, User $guardian, GroupRegistration $registration)
    {
        DB::beginTransaction();

        foreach ($registration->groups() as $group) {
            foreach ($registration->playerInfo($group->program) as $playerId => $playerData) {
                $playerData['group_id'] = $group->id;
                $season->players()->attach($playerId, $playerData);
            }

            Mail::to($guardian)->queue(new RegistrationConfirmation($guardian, $group, $registration));

            /** @var User $user */
            foreach ($group->users()->with('roles')->get() as $user) {
                if ($user->isA(Role::HEAD_COACH) && $user->settings->shouldBeNotifiedWhenUserJoinsGroup()) {
                    Mail::to($user)->queue(new NewRegistrationNotification($guardian, $group, $registration));
                }
            }
        }

        DB::commit();

        event('players.registered.with.group', [
            $group,
            $guardian,
        ]);

        return $group;
    }
}
