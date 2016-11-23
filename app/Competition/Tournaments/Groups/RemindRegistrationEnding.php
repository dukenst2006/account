<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\Tournament;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemindRegistrationEnding extends Command
{
    const COMMAND = 'biblebowl:tournament-registration-ending-reminder';

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
    protected $description = 'Remind all groups that registration ends soon';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $season = Season::current()->firstOrFail();
        /** @var Tournament $tournament */
        foreach (Tournament::visible($season)->active()->get() as $tournament) {
            if (Carbon::now()->isSameDay($tournament->registration_end->subDays(7))) {
                foreach ($tournament->teamSets()->with('group')->get() as $teamSet) {
                    foreach ($teamSet->group->users()->with('roles')->get() as $user) {
                        if ($user->isA(Role::HEAD_COACH)) {
                            $user->notify(new RegistrationEnding($tournament));
                        }
                    }
                }
            }
        }
    }
}
