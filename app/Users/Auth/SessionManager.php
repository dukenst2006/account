<?php namespace BibleBowl\Users\Auth;

use BibleBowl\Group;
use BibleBowl\Season;

class SessionManager extends \Illuminate\Session\SessionManager
{

    const SEASON = 'season';
    const GROUP = 'group';
    const REGISTER_WITH_GROUP = 'register_with_group';

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

    /**
     * @param string $guid
     */
    public function setGroupToRegisterWith($guid)
    {
        $this->set(self::REGISTER_WITH_GROUP, $guid);
    }

    /**
     * @return Group|null
     */
    public function getGroupToRegisterWith()
    {
        return Group::where('guid', $this->get(self::REGISTER_WITH_GROUP))->first();
    }
}
