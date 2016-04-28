<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Season;
use Illuminate\Console\Command;
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
        // if it ends today, start the new season
        $endDate = Setting::seasonEnd();
        if ($endDate->isBirthday()) {
            $startDate = Setting::seasonStart();
            Season::firstOrCreate([
                'name' => $startDate->format("Y-").($startDate->addYear()->format("y"))
            ]);

            // since the season rotated today, deactivate inactive groups from last season
            $lastSeason = Season::orderBy('id', 'DESC')->skip(1)->first();

            $groupDeactivator->deactivateInactiveGroups($lastSeason);
        }
    }
}
