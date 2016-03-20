<?php namespace BibleBowl\Seasons;

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
    public function fire()
    {
        // if it ends today, start the new season
        $endDate = Setting::seasonEnd();
        if ($endDate->isBirthday()) {
            $startDate = Setting::seasonStart();
            Season::firstOrCreate([
                'name' => $startDate->format("Y-").($startDate->addYear()->format("y"))
            ]);
        }
    }
}
