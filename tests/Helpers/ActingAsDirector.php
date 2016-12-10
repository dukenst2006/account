<?php

namespace Helpers;

use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Users\Auth\SessionManager;
use DatabaseSeeder;

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
                SessionManager::SEASON  => $this->season->toArray(),
            ]);
    }

    public function loginAdminAs(string $email)
    {
        $headCoach = User::where('email', $email)->first();
        $this
            ->visit('/admin/switchUser/'.$headCoach->id)
            ->assertEquals($headCoach->email, $this->app['auth.driver']->user()->email);
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
