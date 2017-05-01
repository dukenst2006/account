<?php

namespace App\Seasons;

use App\Groups\RosterSharing\ShareGraduatingPlayers;
use App\Program;
use App\Role;
use App\Season;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;
use Setting;

class SeasonRotator extends Command
{
    const COMMAND = 'biblebowl:rotate-season';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = self::COMMAND;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Begin the next season if the current date indicates it's time";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire(AutomatedGroupDeactivator $groupDeactivator)
    {
        /** @var Carbon $endDate */
        $endDate = Setting::seasonEnd();
        /** @var Carbon $startDate */
        $startDate = Setting::seasonStart();

        $nextSeasonName = $startDate->format('Y-').($startDate->addYear()->format('y'));

        // notify the office before the season rotates
        $rotateInDays = 7;
        if ($endDate->isBirthday(Carbon::now()->addDays($rotateInDays))) {
            Mail::to(config('biblebowl.officeEmail'))->queue(new SeasonRotationReminder($endDate, $nextSeasonName));
        }

        // rotate the season
        if ($endDate->isBirthday()) {
            /* @var Season $season */
            Season::firstOrCreate([
                'name' => $nextSeasonName,
            ]);

            // since the season rotated today, deactivate inactive groups from last season
            $lastSeason = Season::orderBy('id', 'DESC')->skip(1)->first();

            // Share graduating players with an older group
            $program = Program::findOrFail(Program::BEGINNER);
            foreach ($program->groups()->active()->get() as $group) {
                if ($group->settings->hasGroupToShareRosterWith()) {
                    foreach ($group->settings->groupToShareRosterWith()->users()->with('roles')->get() as $user) {
                        if ($user->isA(Role::HEAD_COACH)) {
                            Mail::to($user)->send(new ShareGraduatingPlayers($group, $program, $lastSeason));
                        }
                    }
                }
            }

            $groupDeactivator->deactivateInactiveGroups($lastSeason);
        }
    }
}
