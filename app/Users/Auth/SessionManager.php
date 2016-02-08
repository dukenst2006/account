<?php namespace BibleBowl\Users\Auth;

use Auth;
use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\Seasons\SeasonalRegistration;
use BibleBowl\User;

class SessionManager extends \Illuminate\Session\SessionManager
{

    const SEASON = 'season';
    const GROUP = 'group';
    const REGISTER_WITH_GROUP = 'register_with_group';
    const ADMIN_USER = 'admin_user';
    const SEASONAL_REGISTRATION = 'seasonal_registration';

    /** @var Season */
    protected $season = null;

    /** @var Group */
    protected $group = null;

    /**
     * Clear session items
     */
    public function switchUser(User $user)
    {
        $this->rememberAdminStatus(Auth::user()->id);

        // when switching from a head coach, gotta clear the group
        $this->setGroup(null);

        Auth::loginUsingId($user->id);
    }

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

    public function setGroup(Group $group = null)
    {
        $this->group = $group;
        if (!is_null($group)) {
            $group = $group->toArray();
        }

        $this->set(self::GROUP, $group);
    }

    /**
     * @param SeasonalRegistration $seasonalRegistration
     */
    public function setSeasonalRegistration(SeasonalRegistration $seasonalRegistration)
    {
        $this->set(self::SEASONAL_REGISTRATION, $seasonalRegistration->toArray());
    }

    /**
     * @return SeasonalRegistration
     */
    public function seasonalRegistration()
    {
        $registrationInfo = $this->get(self::SEASONAL_REGISTRATION, []);
        return app(SeasonalRegistration::class, [$registrationInfo]);
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

    public function rememberAdminStatus($adminId)
    {
        $this->set(self::ADMIN_USER, $adminId);
    }

    public function forgetAdminStatus()
    {
        $this->forget(self::ADMIN_USER);
    }

    /**
     * Determine if the current user has access to an admin
     */
    public function canSwitchToAdmin()
    {
        return (bool) $this->get(self::ADMIN_USER);
    }

    public function adminUser()
    {
        return User::findOrFail($this->get(self::ADMIN_USER));
    }
}
