<?php

namespace App\Support\Bugs;

use App\Group;
use App\Tournament;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class NotifyOfBug extends Command
{
    const COMMAND = 'biblebowl:notify-bug';

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
    protected $description = 'Send notification of a bug';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $tournament = Tournament::findOrFail(1);
        $groups = Group::whereHas('teamSets', function (Builder $q) use ($tournament) {
            $q->where('tournament_id', $tournament->id);
        })->get();

        foreach ($groups as $group) {
            foreach ($group->users as $user) {
                $user->notify(new BugFixed($tournament, $group));
            }
        }
    }
}
