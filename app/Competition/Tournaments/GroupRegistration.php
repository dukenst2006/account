<?php

namespace BibleBowl\Competition\Tournaments;

use BibleBowl\TeamSet;
use BibleBowl\Tournament;
use Illuminate\Support\Fluent;

class GroupRegistration extends Fluent
{

    protected $attributes = [
        'tournamentId'    => null,
        'teamSetId'       => null,
        'quizmasters'     => []
    ];

    public function setTournament(Tournament $tournament)
    {
        $this->attributes['tournamentId'] = $tournament->id;
    }

    public function tournament() : Tournament
    {
        return Tournament::find($this->attributes['tournamentId']);
    }

    public function setTeamSet(TeamSet $teamSet)
    {
        $this->attributes['teamSetId'] = $teamSet->id;
    }

    public function teamSet() : TeamSet
    {
        return TeamSet::find($this->attributes['teamSetId']);
    }

    public function addQuizmaster(Quizmasterable $quizmaster)
    {
        $this->attributes['teamSetId'] = $quizmaster->id;
    }

    public function quizmasters() : array
    {
        //return TeamSet::findOrFail($this->attributes['teamSetId']);
    }

}