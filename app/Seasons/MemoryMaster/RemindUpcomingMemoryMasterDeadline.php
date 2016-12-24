<?php

namespace BibleBowl\Seasons\MemoryMaster;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\Season;
use Illuminate\Console\Command;

class RemindUpcomingMemoryMasterDeadline extends Command
{
    const COMMAND = 'biblebowl:memory-master-deadline-reminder';

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
    protected $description = 'Send reminders to head coaches before the memory master deadline hits';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // only a week before
//        if (!Carbon::now()->isSameDay(Setting::memoryMasterDeadline()->subDays(7))) {
//            return;
//        }

        $season = Season::current()->firstOrFail();

        /** @var Group[] $groups */
        $groups = Group::active()->with('owner', 'meetingAddress')->get();

        foreach ($groups as $group) {
            if ($group->players()->haveNotAchievedMemoryMaster($season)->count() > 0) {
                foreach ($group->users()->with('roles')->get() as $user) {
                    if ($user->isA(Role::HEAD_COACH)) {
                        $user->notify(new UpcomingMemoryMasterDeadline());
                    }
                }
            }
        }
    }
}
