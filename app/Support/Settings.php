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
        $seasonEnd = Carbon::createFromTimestamp(strtotime($this->get('season_end', 'July 10')));

        // if August or later, end date represents next year
        if ($seasonEnd->format('m') >= 8) {
            return $seasonEnd->addYear();
        }

        return $seasonEnd;
    }

    /**
     * @return Carbon
     */
    public function seasonStart()
    {
        $seasonEnd = $this->seasonEnd();

        // season has already rotated, should be next year
        if ($seasonEnd->lt(Carbon::now())) {
            $seasonEnd->addYear(1);
        }

        return $seasonEnd;
    }

    /**
     * @param Carbon $date
     */
    public function setSeasonEnd(Carbon $date)
    {
        $this->set('season_end', $date->format('F j'));
    }
}
