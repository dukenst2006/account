<?php namespace BibleBowl;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {
    const DIRECTOR          = 'director';
    const DIRECTOR_ID       = 1;

    const HEAD_COACH        = 'head-coach';
    const HEAD_COACH_ID     = 2;

    const BOARD_MEMBER      = 'board-member';
    const BOARD_MEMBER_ID   = 3;

    const RR_COORDINATOR    = 'rr-coordinator';
    const RR_COORDINATOR_ID = 4;

    const QUIZMASTER        = 'quizmaster';
    const QUIZMASTER_ID     = 5;

    const COACH             = 'coach';
    const COACH_ID          = 6;

    const GUARDIAN          = 'guardian';
    const GUARDIAN_ID       = 7;

    protected $guarded = ['id'];
}
