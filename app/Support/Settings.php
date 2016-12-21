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
        if (Carbon::now()->gt($seasonEnd)) {
            return $seasonEnd->addYear();
        }

        return $seasonEnd;
    }

    public function seasonStart() : Carbon
    {
        $seasonEnd = $this->seasonEnd();

        // season has already rotated, should be next year
        if ($seasonEnd->lt(Carbon::now())) {
            $seasonEnd->addYear();
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

        // season has already ended this year, should be next year
        if ($memoryMasterDeadline->lt(Carbon::now())) {
            $memoryMasterDeadline->addYear();
        }

        return $memoryMasterDeadline;
    }

    public function setMemoryMasterDeadline(Carbon $date)
    {
        $this->set('memory_master_deadline', $date->format('F j'));
    }
}
