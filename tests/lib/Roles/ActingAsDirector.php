<?php namespace Lib\Roles;

use DatabaseSeeder;
use BibleBowl\User;
use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\Users\Auth\SessionManager;

trait ActingAsDirector
{
    /** @var User */
    private $director;

    /** @var Season */
    private $season;

    public function setupAsDirector()
    {
        $this->director = User::where('email', DatabaseSeeder::DIRECTOR_EMAIL)->first();
        $this->season = Season::orderBy('id', 'DESC')->first();

        $this->actingAs($this->director)
            ->withSession([
                SessionManager::SEASON  => $this->season->toArray()
            ]);
    }

    public function season()
    {
        return $this->season;
    }

    public function director()
    {
        return $this->director;
    }
}