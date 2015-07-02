<?php namespace BibleBowl\Auth;

use BibleBowl\Season;
use BibleBowl\Group;

class SessionManager extends \Illuminate\Session\SessionManager
{

    const SEASON = 'season';
    const GROUP = 'group';

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

    /**
     * @return Group
     */
    public function group()
    {
        Group::unguard();
        $group = $this->app->make(Group::class, [$this->get(self::GROUP)]);
        Group::reguard();
        return $group;
    }

    public function setGroup(Group $group)
    {
        $this->set(self::SEASON, $group->toArray());
    }
}
