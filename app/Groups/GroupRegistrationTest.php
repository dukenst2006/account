<?php

namespace App\Groups;

use App\Group;
use App\Player;
use App\Program;
use App\Seasons\GroupRegistration;

/**
 * This class is a mock of a group registration
 * used when a Head Coach sends a test Welcome Email
 * to themselves.
 */
class GroupRegistrationTest extends GroupRegistration
{
    const TEST_PLAYER_ID_1 = 1;
    const TEST_PLAYER_ID_2 = 2;

    /**
     * Seed 2 players for each Group.
     *
     * @param Group $group
     */
    public function addGroup(Group $group)
    {
        parent::addGroup($group);

        $this->addPlayer(self::TEST_PLAYER_ID_1, $group->program->min_grade, 'SM');
        $this->addPlayer(self::TEST_PLAYER_ID_2, $group->program->max_grade, 'M');
    }

    public function players(Program $program)
    {
        return collect([
            factory(Player::class)->make([
                'id' => self::TEST_PLAYER_ID_1,
            ]),
            factory(Player::class)->make([
                'id' => self::TEST_PLAYER_ID_2,
            ]),
        ]);
    }
}
