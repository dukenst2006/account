<?php namespace BibleBowl\Seasons;

use Carbon\Carbon;
use Config;

class SeasonCalendar
{
    /**
     * Get the date the current season should end
     *
     * @return Carbon
     */
    public function endDate()
    {
        $seasonEndDate = Carbon::now(Config::get('biblebowl.season.end'))->startOfDay();

        // if August or later, end date represents next year
        if ($seasonEndDate->format('m') >= 8) {
            return $seasonEndDate->addYear();
        }

        return $seasonEndDate;
    }

    /**
     * Get the date the next season should begin
     *
     * @return Carbon
     */
    public function startDate()
    {
        return $this->endDate();
    }
}