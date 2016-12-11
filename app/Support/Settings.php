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

    public function seasonEnd() : Carbon
    {
        $seasonEnd = Carbon::createFromTimestamp(strtotime($this->get('season_end', 'July 10')));

        // if August or later, end date represents next year
        if ($seasonEnd->format('m') >= 8) {
            return $seasonEnd->addYear();
        }

        return $seasonEnd;
    }

    public function seasonStart() : Carbon
    {
        $seasonEnd = $this->seasonEnd();

        // season has already rotated, should be next year
        if ($seasonEnd->lt(Carbon::now())) {
            $seasonEnd->addYear(1);
        }

        return $seasonEnd;
    }

    public function setSeasonEnd(Carbon $date)
    {
        $this->set('season_end', $date->format('F j'));
    }

    public function memoryMasterDeadline() : Carbon
    {
        $memoryMasterDeadline = Carbon::createFromTimestamp(strtotime($this->get('memory_master_deadline', 'May 1')));

        // if August or later, end date represents next year
        if ($memoryMasterDeadline->format('m') <= 8) {
            return $memoryMasterDeadline->addYear();
        }

        return $memoryMasterDeadline;
    }

    public function setMemoryMasterDeadline(Carbon $date)
    {
        $this->set('memory_master_deadline', $date->format('F j'));
    }
}
