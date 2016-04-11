<?php

namespace BibleBowl\Support;

use anlutro\LaravelSettings\SettingsManager;
use Carbon\Carbon;

class Settings extends SettingsManager
{
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        parent::__construct($app);
    }

    /**
     * @return Carbon
     */
    public function seasonEnd()
    {
        $seasonEnd = Carbon::createFromTimestamp(strtotime($this->get('season_end', 'July 30')));

        // if August or later, end date represents next year
        if ($seasonEnd->format('m') >= 8) {
            return $seasonEnd->addYear();
        }

        return $seasonEnd;
    }

    /**
     * @return Carbon
     */
    public function startDate()
    {
        return $this->seasonEnd();
    }

    /**
     * @param Carbon $date
     */
    public function setSeasonEnd(Carbon $date)
    {
        $this->set('season_end', $date->format('F j'));
    }
}
