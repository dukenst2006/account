<?php

namespace BibleBowl\Competition\Tournaments\Quizmasters;

use Illuminate\Support\Fluent;

class QuizzingPreferences extends Fluent
{
    public function __construct($attributes = [])
    {
        if ($attributes !== null) {
            parent::__construct($attributes);
        }
    }

    public function quizzedAtThisTournamentBefore()
    {
        return $this->get('quizzedAtThisTournamentBefore', false);
    }

    public function setQuizzedAtThisTournamentBefore($quizzedAtThisTournamentBefore)
    {
        $this->quizzedAtThisTournamentBefore = $quizzedAtThisTournamentBefore;
    }

    public function timesQuizzedAtThisTournament()
    {
        return $this->get('timesQuizzedAtThisTournament', '');
    }

    public function setTimesQuizzedAtThisTournament($timesQuizzedAtThisTournament)
    {
        $this->timesQuizzedAtThisTournament = $timesQuizzedAtThisTournament;
    }

    public function gamesQuizzedThisSeason()
    {
        return $this->get('gamesQuizzedThisSeason', 0);
    }

    public function setGamesQuizzedThisSeason($gamesQuizzedThisSeason)
    {
        $this->gamesQuizzedThisSeason = $gamesQuizzedThisSeason;
    }

    public function quizzingInterest()
    {
        return $this->get('quizzingInterest', 0);
    }

    public function setQuizzingInterest($quizzingInterest)
    {
        $this->quizzingInterest = $quizzingInterest;
    }

    public function quizzingFrequency()
    {
        return $this->get('quizzingFrequency', 0);
    }

    public function setQuizzingFrequency($quizzingFrequency)
    {
        $this->quizzingFrequency = $quizzingFrequency;
    }
}
