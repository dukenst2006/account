<?php namespace Lib;

use BibleBowl\Auth\SessionManager;
use BibleBowl\Season;

trait CurrentSeasonInSession
{
    public function currentSeasonInSession()
    {
        $this->withSession([
            SessionManager::SEASON => Season::current()->first()->toArray()
        ]);
    }
}