<?php namespace BibleBowl\Auth;

use BibleBowl\Season;
use BibleBowl\Group;

class SessionManager extends \Illuminate\Session\SessionManager
{

    const SEASON = 'season';
    const GROUP = 'group';

    /** @var Season */
    protected $season = null;

    /** @var Group */
    protected $group = null;

    /**
     * @return Season
     */
    public function season()
    {
        if (is_null($this->season)) {
            Season::unguard();
            $this->season = $this->app->make(Season::class, [$this->get(self::SEASON)]);
            Season::reguard();
        }

        return $this->season;
    }

    public function setSeason(Season $season)
    {
        $this->season = $season;
        $this->set(self::SEASON, $season->toArray());
    }

    /**
     * @return Group
     */
    public function group()
    {
        if (is_null($this->group)) {
            Group::unguard();
            $this->group = $this->app->make(Group::class, [$this->get(self::GROUP)]);
            Group::reguard();
        }

        return $this->group;
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
        $this->set(self::GROUP, $group->toArray());
    }
}
