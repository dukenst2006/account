<?php

namespace BibleBowl\Competition\Tournaments;

use Illuminate\Support\Fluent;

class Settings extends Fluent
{
    const DEFAULT_MAXIMUM_PLAYERS_PER_TEAM = 6;
    const DEFAULT_MINIMUM_PLAYERS_PER_TEAM = 3;

    public function __construct($attributes = [])
    {
        if ($attributes !== null) {
            parent::__construct($attributes);
        }
    }

    public function quizmasterRequirement() : string
    {
        return $this->get('requireQuizmasters', 'none');
    }

    public function shouldRequireQuizmasters() : bool
    {
        return $this->get('requireQuizmasters', 'none') != 'none';
    }

    public function shouldRequireQuizmastersByGroup() : bool
    {
        return $this->get('requireQuizmasters', null) == 'group';
    }

    public function quizmastersToRequireByGroup() : int
    {
        return $this->get('quizmastersToRequireByGroup', 1);
    }

    public function setQuizmastersToRequireByGroup(int $quizmasterCount)
    {
        $this->quizmastersToRequireByGroup = $quizmasterCount;
    }

    public function shouldRequireQuizmastersByTeamCount() : bool
    {
        return $this->get('requireQuizmasters', null) == 'team_count';
    }

    public function quizmastersToRequireByTeamCount() : int
    {
        return $this->get('quizmastersToRequireByTeamCount', 1);
    }

    public function setQuizmastersToRequireByTeamCount(int $teamCount)
    {
        $this->quizmastersToRequireByTeamCount = $teamCount;
    }

    public function teamCountToRequireQuizmastersBy() : int
    {
        return $this->get('teamCountToRequireQuizmastersBy', 2);
    }

    public function setTeamCountToRequireQuizmastersBy(int $teamCount)
    {
        $this->teamCountToRequireQuizmastersBy = $teamCount;
    }

    public function requireQuizmasters(string $type)
    {
        $this->requireQuizmasters = $type;
    }

    public function shouldCollectShirtSizes() : bool
    {
        return (bool) $this->get('collectShirtSizes', true);
    }

    public function collectShirtSizes(bool $collectShirtSizes)
    {
        $this->collectShirtSizes = $collectShirtSizes ? '1' : '0';
    }

    public function shouldCollectQuizmasterPreferences() : bool
    {
        return (bool) $this->get('collectQuizmasterPreferences', true);
    }

    public function collectQuizmasterPreferences(bool $collectQuizmasterPreferences)
    {
        $this->collectQuizmasterPreferences = $collectQuizmasterPreferences ? '1' : '0';
    }

    public function maximumPlayersPerTeam() : int
    {
        return $this->get('maximumPlayersPerTeam', self::DEFAULT_MAXIMUM_PLAYERS_PER_TEAM);
    }

    public function setMaximumPlayersPerTeam(int $maximumPlayersPerTeam)
    {
        $this->maximumPlayersPerTeam = $maximumPlayersPerTeam;
    }

    public function minimumPlayersPerTeam() : int
    {
        return $this->get('minimumPlayersPerTeam', self::DEFAULT_MINIMUM_PLAYERS_PER_TEAM);
    }

    public function setMinimumPlayersPerTeam(int $minimumPlayersPerTeam)
    {
        $this->minimumPlayersPerTeam = $minimumPlayersPerTeam;
    }
}
