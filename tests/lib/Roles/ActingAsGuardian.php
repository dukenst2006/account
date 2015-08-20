<?php namespace Lib\Roles;

use DatabaseSeeder;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\Users\Auth\SessionManager;

trait ActingAsGuardian
{
    /** @var User */
    private $guardian;

    /** @var Season */
    private $season;

    public function setupAsGuardian()
    {
        $this->guardian = User::where('email', DatabaseSeeder::GUARDIAN_EMAIL)->first();
        $this->season = Season::first();

        $this->actingAs($this->guardian)
            ->withSession([
                SessionManager::SEASON  => $this->season->toArray()
            ]);
    }

    public function season()
    {
        return $this->season;
    }

    public function guardian()
    {
        return $this->guardian;
    }
}