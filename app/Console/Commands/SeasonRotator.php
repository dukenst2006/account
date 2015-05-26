<?php namespace BibleBowl\Console\Commands;

use App;
use BibleBowl\Season;
use BibleBowl\Competition\SeasonCalendar;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SeasonRotator extends Command {

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
		/** @var SeasonCalendar $seasonCalendar */
		$seasonCalendar = App::make(SeasonCalendar::class);

		// if it ends today, start the new season
		$endDate = $seasonCalendar->endDate();
		$startDate = $seasonCalendar->startDate();
		if ($endDate->eq(Carbon::today())) {
			Season::firstOrCreate([
				'name' => $startDate->format("Y-").($startDate->addYear()->format("y"))
			]);
		}
	}

}
