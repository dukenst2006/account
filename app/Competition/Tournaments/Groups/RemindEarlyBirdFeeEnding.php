<?php

namespace BibleBowl\Competition\Tournaments\Groups;

use BibleBowl\Group;
use BibleBowl\Program;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\Tournament;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemindEarlyBirdFeeEnding extends Command
{
    const COMMAND = 'biblebowl:tournament-early-bird-fee-reminder';

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
    protected $description = 'Remind unpaid tournament registrations that the early bird fees are expiring soon';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $season = Season::current()->firstOrFail();
        foreach (Program::all() as $program) {
            /** @var Tournament $tournament */
            foreach (Tournament::visible($program->id, $season)->active()->get() as $tournament) {
                if ($tournament->hasEarlyBirdRegistration() && Carbon::now()->isSameDay($tournament->earlybird_ends->subDays(7))) {
                    /** @var Group[] $groups */
                    $groups = Group::hasPendingTournamentRegistrationFees($tournament)->get();
                    foreach ($groups as $group) {
                        foreach ($group->users()->with('roles')->get() as $user) {
                            if ($user->isA(Role::HEAD_COACH)) {
                                $user->notify(new EarlyBirdExpirationNotification($tournament));
                            }
                        }
                    }
                }
            }
        }
    }
}
