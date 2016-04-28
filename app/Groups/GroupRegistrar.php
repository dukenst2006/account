<?php namespace BibleBowl\Groups;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\Seasons\GroupRegistration;
use BibleBowl\User;
use DB;
use Illuminate\Mail\Message;
use Mail;

class GroupRegistrar
{
    /** @var RegistrationConfirmation */
    protected $registrationConfirmation;

    public function __construct(RegistrationConfirmation $registrationConfirmation)
    {
        $this->registrationConfirmation = $registrationConfirmation;
    }

    public function register(Season $season, User $guardian, GroupRegistration $registration)
    {
        DB::beginTransaction();

        foreach ($registration->groups() as $group) {
            foreach ($registration->playerInfo($group->program) as $playerId => $playerData) {
                $playerData['group_id'] = $group->id;
                $season->players()->attach($playerId, $playerData);
            }

            // Since this email is queued, we need to get pivot data now and include it with $players
            // because once it actually gets processed $players won't be an object making it more
            // difficult to fetch this data
            $players = [];
            foreach ($registration->players($group->program) as $player) {
                $player->full_name  = $player->full_name;
                $player->age        = $player->age();
                $player->shirt_size = $registration->shirtSize($player->id);
                $player->grade      = $registration->grade($player->id);
                $players[] = $player;
            }

            // setting this value so that it's available in the toArray() so queued mail can use it
            $guardian->full_name = $guardian->full_name;

            /** @var User $user */
            foreach ($group->users()->with('roles')->get() as $user) {
                if ($user->is(Role::HEAD_COACH) && $user->settings->shouldBeNotifiedWhenUserJoinsGroup()) {
                    Mail::queue(
                        'emails.group-registration-notification',
                        [
                            'groupId'   => $group->id,
                            'guardian'  => $guardian,
                            'players'   => $players
                        ],
                        function (Message $message) use ($group, $user, $players) {
                            $message->to($user->email, $user->full_name)
                                ->subject('New '.$group->name.' Registration'.(count($players) > 1 ? 's' : ''));
                        }
                    );
                }
            }

            $this->registrationConfirmation->send($guardian, $group, $registration);
        }

        DB::commit();

        event('players.registered.with.group', [
            $group,
            $guardian
        ]);

        return $group;
    }
}
