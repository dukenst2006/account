<?php namespace BibleBowl\Groups;

use Illuminate\Mail\Message;
use Mail;
use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\User;
use DB;

class GroupRegistrar
{

    public function register(Season $season, Group $group, User $guardian, array $playerIds)
    {
        DB::beginTransaction();

        $season->players()
            ->where('guardian_id', $guardian->id)
            ->wherePivot('season_id', $season->id)
            ->updateExistingPivot($playerIds, [
                'group_id' => $group->id
            ]);

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

        $guardian->full_name = $guardian->full_name;

        /** @var User $user */
        foreach ($group->users()->with('roles')->get() as $user) {
            if ($user->hasRole(Role::HEAD_COACH) && $user->settings->shouldBeNotifiedWhenUserJoinsGroup()) {

                Mail::queue(
                    'emails.group-registration-confirmation',
                    [
                        'group'     => $group,
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