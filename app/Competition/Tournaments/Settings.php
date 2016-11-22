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
