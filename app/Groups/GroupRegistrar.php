<?php namespace BibleBowl\Groups;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\User;
use DB;
use Illuminate\Mail\Message;
use Mail;

class GroupRegistrar
{

    public function register(Season $season, Group $group, User $guardian, array $playerIds)
    {
        DB::beginTransaction();

        foreach ($playerIds as $playerId) {
            $season->players()
                ->where('guardian_id', $guardian->id)
                ->updateExistingPivot($playerId, [
                    'group_id' => $group->id
                ]);
        }

        // Since this email is queued, we need to get pivot data now and include it with $players
        // because once it actually gets processed $players won't be an object making it more
        // difficult to fetch this data
        $players = [];
        foreach ($group->players()->whereIn('players.id', $playerIds)->get() as $idx => $player) {
            $player->full_name  = $player->full_name;
            $player->age        = $player->age();
            $player->shirt_size = $player->pivot->shirt_size;
            $player->grade      = $player->pivot->grade;
            $players[] = $player;
        }

        // setting this value so that it's available in the toArray() so queued mail can use it
        $guardian->full_name = $guardian->full_name;

        /** @var User $user */
        foreach ($group->users()->with('roles')->get() as $user) {
            if ($user->hasRole(Role::HEAD_COACH) && $user->settings->shouldBeNotifiedWhenUserJoinsGroup()) {

                Mail::queue(
                    'emails.group-registration-confirmation',
                    [
                        'groupId'   => $group->id,
                        'guardian'  => $guardian,
                        'players'   => $players
                    ],
                    function(Message $message) use ($group, $user, $players)
                    {
                        $message->to($user->email, $user->full_name)
                            ->subject('New '.$group->full_name.' Registration'.(count($players) > 1 ? 's' : ''));
                    }
                );
            }
        }

        DB::commit();

        return $group;
    }
}