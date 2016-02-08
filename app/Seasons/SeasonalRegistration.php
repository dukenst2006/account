<?php

namespace BibleBowl\Seasons;

use BibleBowl\Group;
use BibleBowl\Player;
use BibleBowl\Program;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

class SeasonalRegistration extends Fluent
{
    protected $players = null;

    protected $programs = null;

    protected $attributes = [
        'groups' => [],
        'players' => []
    ];

    /** @var Group */
    protected $group;

    /**
     * @param Group $group
     */
    public function setGroup(Program $program, Group $group)
    {
        $this->attributes['groups'][$program->id] = $group->id;
    }

    /**
     * User looked, but couldn't find their group
     *
     * @param Group $group
     */
    public function noGroupFound(Program $program)
    {
        $this->attributes['groups'][$program->id] = false;
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
     * @param Program $program
     * @return Group
     */
    public function group(Program $program)
    {
        return Group::findOrFail($this->groups[$program->id]);
    }

    /**
     * Determine if this registration has specified a group
     *
     * @param Program $program
     * @return bool
     */
    public function hasGroup(Program $program)
    {
        return isset($this->groups[$program->id]) && $this->groups[$program->id] > 0;
    }

    /**
     * If this registration has attempted to find their
     * group yet for a given program
     *
     * @param Program $program
     * @return bool
     */
    public function hasLookedForGroup(Program $program)
    {
        return isset($this->groups[$program->id]) &&  (
            $this->groups[$program->id] === false || $this->hasGroup($program)
        );
    }

    /**
     * @return bool
     */
    public function hasLookedForAllGroups()
    {
        foreach($this->programs() as $program) {
            if ($this->hasLookedForGroup($program) === false) {
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
            'shirt_size'    => $shirtSize
        ];
    }

    /**
     * Get players for a given program
     *
     * @return Collection
     */
    public function players(Program $program)
    {
        return Player::whereIn('id', array_keys($this->playerInfo($program)->toArray()))->get();
    }

    /**
     * Get player's registration information that are
     * eligible for a given program
     *
     * @return Collection
     */
    public function playerInfo(Program $program)
    {
        if ($this->players == null) {
            /** @var Collection $players */
            $this->players = app(Collection::class, [
                $this->get('players', [])
            ]);
        }

        return $this->players->filter(function ($player) use ($program) {
            if ($player['grade'] >= $program->min_grade && $player['grade'] <= $program->max_grade) {
                return $player;
            }
        });
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
}