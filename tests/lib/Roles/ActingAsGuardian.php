<?php namespace Lib\Roles;

use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Users\Auth\SessionManager;
use DatabaseSeeder;

trait ActingAsGuardian
{
    /** @var User */
    private $guardian;

    /** @var Season */
    private $season;

    public function setupAsGuardian()
    {
        $this->guardian = User::where('email', DatabaseSeeder::GUARDIAN_EMAIL)->first();
        $this->season = Season::orderBy('id', 'DESC')->first();

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