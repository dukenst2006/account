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
        Season::unguard();
        $season = $this->app->make(Season::class, [$this->get(self::SEASON)]);
        Season::reguard();
        return $season;
    }

    public function setSeason(Season $season)
    {
        $this->set(self::SEASON, $season->toArray());
    }
}
