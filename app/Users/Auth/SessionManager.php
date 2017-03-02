<?php

namespace App\Users\Auth;

use App\Competition\Tournaments as TournamentGroupRegistration;
use App\Group;
use App\Invitation;
use App\Season;
use App\Seasons\GroupRegistration;
use App\User;
use Auth;

class SessionManager extends \Illuminate\Session\SessionManager
{
    const SEASON = 'season';
    const GROUP = 'group';
    const CART = 'cart';
    const PENDING_INVITATION = 'pending_invitation';
    const REGISTER_WITH_GROUP = 'register_with_group';
    const ADMIN_USER = 'admin_user';
    const SEASONAL_GROUP_REGISTRATION = 'seasonal_group_registration';
    const TOURNAMENT_GROUP_REGISTRATION = 'tournament_group_registration';
    const REDIRECT_TO_AFTER_AUTH = 'after_auth_redirect';

    /** @var Season */
    protected $season = null;

    /** @var Group */
    protected $group = null;

    /**
     * Clear session items.
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
            $this->season = new Season($this->get(self::SEASON));
            Season::reguard();
        }

        return $this->season;
    }

    public function setSeason(Season $season)
    {
        $this->season = $season;
        $this->put(self::SEASON, $season->toArray());
    }

    /**
     * @return Group
     */
    public function group()
    {
        if (is_null($this->group) && $this->get(self::GROUP) != null) {
            Group::unguard();
            $this->group = new Group($this->get(self::GROUP));
            Group::reguard();
        }

        return $this->group;
    }

    public function doesntHaveGroup() : bool
    {
        return !$this->hasGroup();
    }

    public function hasGroup() : bool
    {
        return $this->get(self::GROUP, null) != null;
    }

    public function setGroup(Group $group = null)
    {
        $this->group = $group;
        if (!is_null($group)) {
            $group = $group->toArray();
        }

        $this->put(self::GROUP, $group);
    }

    /**
     * @param GroupRegistration $seasonalGroupRegistration
     */
    public function setSeasonalGroupRegistration(GroupRegistration $seasonalGroupRegistration)
    {
        $this->put(self::SEASONAL_GROUP_REGISTRATION, $seasonalGroupRegistration->toArray());
    }

    /**
     * @return GroupRegistration
     */
    public function seasonalGroupRegistration()
    {
        $registrationInfo = $this->get(self::SEASONAL_GROUP_REGISTRATION, []);

        return new GroupRegistration($registrationInfo);
    }

    /**
     * @param TournamentGroupRegistration $seasonalGroupRegistration
     */
    public function setTournamentGroupRegistration(TournamentGroupRegistration $seasonalGroupRegistration)
    {
        $this->put(self::TOURNAMENT_GROUP_REGISTRATION, $seasonalGroupRegistration->toArray());
    }

    /**
     * @return GroupRegistration
     */
    public function tournamentGroupRegistration() : TournamentGroupRegistration
    {
        $registrationInfo = $this->get(self::TOURNAMENT_GROUP_REGISTRATION, []);

        return new TournamentGroupRegistration($registrationInfo);
    }

    /**
     * @param Group $group
     */
    public function setGroupToRegisterWith(Group $group)
    {
        $this->put(self::REGISTER_WITH_GROUP, $group->guid);
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
        $this->put(self::ADMIN_USER, $adminId);
    }

    /**
     * @param string $url
     */
    public function setRedirectToAfterAuth($url)
    {
        $this->put(self::REDIRECT_TO_AFTER_AUTH, $url);
    }

    public function redirectToAfterAuth()
    {
        return $this->get(self::REDIRECT_TO_AFTER_AUTH, null);
    }

    public function forgetAdminStatus()
    {
        $this->forget(self::ADMIN_USER);
    }

    /**
     * Determine if the current user has access to an admin.
     */
    public function canSwitchToAdmin()
    {
        return (bool) $this->get(self::ADMIN_USER);
    }

    public function isNotMyself()
    {
        return (bool) $this->get(self::ADMIN_USER);
    }

    public function adminUser()
    {
        return User::findOrFail($this->get(self::ADMIN_USER));
    }

    public function hasPendingInvitation() : bool
    {
        return $this->get(self::PENDING_INVITATION, 0) > 0;
    }

    public function pendingInvitation() : Invitation
    {
        return app(Invitation::class)->find($this->get(self::PENDING_INVITATION));
    }

    public function setPendingInvitation(Invitation $invitation = null)
    {
        if ($invitation == null) {
            return $this->put(self::PENDING_INVITATION, $invitation);
        }

        $this->put(self::PENDING_INVITATION, $invitation->id);
    }
}
