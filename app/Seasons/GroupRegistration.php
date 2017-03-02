<?php

namespace App\Seasons;

use App\Group;
use App\Player;
use App\Program;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

class GroupRegistration extends Fluent
{
    public static $gradesWithProgramChoice = [];

    protected $programs = null;

    protected $attributes = [
        'groups'  => [],
        'players' => [],
    ];

    /** @var Group */
    protected $group;

    /**
     * @param Group $group
     */
    public function addGroup(Group $group)
    {
        $this->attributes['groups'][$group->program_id] = $group->id;
    }

    /**
     * @param Program $program
     *
     * @return Group
     */
    public function group(Program $program)
    {
        return Group::findOrFail($this->attributes['groups'][$program->id]);
    }

    /**
     * @return Group[]
     */
    public function groups()
    {
        return Group::whereIn('id', array_values($this->attributes['groups']))->get();
    }

    /**
     * @param Program $program
     *
     * @return bool
     */
    public function hasGroup(Program $program)
    {
        return isset($this->attributes['groups'][$program->id]);
    }

    /**
     * @return Program
     */
    public function programs()
    {
        if ($this->programs == null) {
            $programs = [];
            foreach (Program::all() as $program) {
                if ($this->numberOfPlayers($program) > 0) {
                    $programs[] = $program;
                }
            }
            $this->programs = $programs;
        }

        return $this->programs;
    }

    /**
     * @return bool
     */
    public function hasFoundAllGroups()
    {
        foreach ($this->programs() as $program) {
            if ($this->hasGroup($program) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $playerId
     * @param $grade
     * @param $shirtSize
     */
    public function addPlayer($playerId, $grade, $shirtSize)
    {
        $this->attributes['players'][$playerId] = [
            'grade'         => $grade,
            'shirt_size'    => $shirtSize,
        ];
    }

    /**
     * Remove given players from registration.
     */
    public function removePlayers(Program $program)
    {
        $playerIds = $this->playerInfo($program)->keys()->toArray();
        $this->attributes['players'] = array_except($this->attributes['players'], $playerIds);
    }

    /**
     * Get players for a given program.
     *
     * @return Player[]|Collection
     */
    public function players(Program $program)
    {
        return Player::whereIn('id', array_keys($this->playerInfo($program)->toArray()))->get();
    }

    /**
     * Get player's registration information that are
     * eligible for a given program.
     *
     * @return Collection
     */
    public function playerInfo(Program $program)
    {
        $players = [];
        foreach ($this->get('players', []) as $playerId => $playerData) {
            // if a parent has overridden the default grade
            if (isset($this->attributes['programOverride'][$playerId])) {
                if ($this->attributes['programOverride'][$playerId] == $program->id) {
                    $players[$playerId] = $playerData;
                }
            } elseif ($playerData['grade'] >= $program->min_grade && $playerData['grade'] <= $program->max_grade) {
                $players[$playerId] = $playerData;
            }
        }

        return new Collection($players);
    }

    /**
     * @return int
     */
    public function grade($playerId)
    {
        return $this->attributes['players'][$playerId]['grade'];
    }

    /**
     * @return string
     */
    public function shirtSize($playerId)
    {
        return $this->attributes['players'][$playerId]['shirt_size'];
    }

    /**
     * @return int
     */
    public function numberOfPlayers(Program $program)
    {
        return $this->playerInfo($program)->count();
    }

    /**
     * Determine if a player in this group requires the parent
     * to choose which program they belong to.
     *
     * @return bool
     */
    public function requiresProgramSelection()
    {
        foreach ($this->attributes['players'] as $player) {
            if (in_array($player['grade'], self::$gradesWithProgramChoice)) {
                return true;
            }
        }

        return false;
    }

    public function overrideProgram($playerId, $programId)
    {
        $this->attributes['programOverride'][$playerId] = $programId;
    }

    /**
     * Get players for a given program.
     *
     * @return Player[]|Collection
     */
    public function playersWithOptionalProgramSelection()
    {
        $playerIds = [];
        foreach ($this->attributes['players'] as $playerId => $playerData) {
            if (in_array($playerData['grade'], self::$gradesWithProgramChoice)) {
                $playerIds[] = $playerId;
            }
        }

        return Player::whereIn('id', $playerIds)->get();
    }
}
