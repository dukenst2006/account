<?php

namespace BibleBowl\Seasons;

use BibleBowl\Group;
use Illuminate\Support\Fluent;

class Registration extends Fluent
{
    protected $attributes = [
        'players' => []
    ];

    /** @var Group */
    protected $group;

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->groupId = $group->id;
    }

    /**
     * @return Group
     */
    public function group()
    {
        if ($this->group == null) {
            $this->group = Group::findOrFail($this->groupId);
        }

        return $this->group;
    }

    /**
     * Determine if this registration has specified a group
     *
     * @return bool
     */
    public function hasGroup()
    {
        return $this->groupId != null;
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
     * @return []
     */
    public function players()
    {
        return $this->get('players', []);
    }

    /**
     * @return int
     */
    public function numberOfPlayers()
    {
        return count($this->players());
    }
}