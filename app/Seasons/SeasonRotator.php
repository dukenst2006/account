<?php namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Program;
use BibleBowl\Season;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Setting;
use Mail;
use Illuminate\Mail\Message;

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

        $nextSeasonName = $startDate->format("Y-").($startDate->addYear()->format("y"));

        // notify the office before the season rotates
        $rotateInDays = 7;
        if ($endDate->isBirthday(Carbon::now()->addDays($rotateInDays))) {
            Mail::queue(
                'emails.season-rotate-notification',
                [
                    'willRotateOn'      => $endDate->toFormattedDateString(),
                    'nextSeasonName'    => $nextSeasonName,
                    'programs'          => Program::orderBy('name', 'ASC')->get()
                ],
                function (Message $message) use ($nextSeasonName, $rotateInDays) {
                    $message->to(config('biblebowl.officeEmail'))
                        ->subject('The '.$nextSeasonName.' season begins in '.$rotateInDays.' days');
                }
            );
        }

        // rotate the season
        if ($endDate->isBirthday()) {
            /** @var Season $season */
            Season::firstOrCreate([
                'name' => $nextSeasonName
            ]);

            // since the season rotated today, deactivate inactive groups from last season
            $lastSeason = Season::orderBy('id', 'DESC')->skip(1)->first();

            $groupDeactivator->deactivateInactiveGroups($lastSeason);
        }
    }
}
