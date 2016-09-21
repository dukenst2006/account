<?php

namespace Helpers;

use BibleBowl\Group;
use BibleBowl\Season;
use BibleBowl\User;
use BibleBowl\Users\Auth\SessionManager;
use DatabaseSeeder;

trait ActingAsHeadCoach
{
    /** @var User */
    private $headCoach;

    /** @var Group */
    private $group;

    /** @var Season */
    private $season;

    public function setupAsHeadCoach()
    {
        $this->headCoach = User::where('email', DatabaseSeeder::HEAD_COACH_EMAIL)->first();
        $this->group = Group::where('name', DatabaseSeeder::GROUP_NAME)->first();
        $this->season = Season::orderBy('id', 'DESC')->first();

        $this->actingAs($this->headCoach)
            ->withSession([
                SessionManager::GROUP   => $this->group->toArray(),
                SessionManager::SEASON  => $this->season->toArray(),
            ]);
    }

    public function season()
    {
        return $this->season;
    }

    public function group()
    {
        return $this->group;
    }

    public function headCoach()
    {
        return $this->headCoach;
    }
}
