<?php namespace BibleBowl\Auth;

use BibleBowl\Season;

class SessionManager extends \Illuminate\Session\SessionManager
{

    const SEASON = 'season';

    /**
     * @return Season
     */
    public function season()
    {
        return $this->app->make(Season::class, $this->get(self::SEASON));
    }

    public function setSeason(Season $season)
    {
        $this->set(self::SEASON, $season->toArray());
    }
}
