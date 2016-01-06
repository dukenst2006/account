<?php namespace BibleBowl;

use Zizaco\Entrust\EntrustRole;

/**
 * BibleBowl\Role
 *
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model')[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.permission')[] $perms
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereUpdatedAt($value)
 * @property string $mailchimp_interest_id
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Role whereMailchimpInterestId($value)
 */
class Role extends EntrustRole {
    const DIRECTOR              = 'director';
    const DIRECTOR_ID           = 1;

    const BOARD_MEMBER          = 'board-member';
    const BOARD_MEMBER_ID       = 2;

    const LEAGUE_COORDINATOR    = 'league-coordinator';
    const LEAGUE_COORDINATOR_ID = 3;

    const HEAD_COACH            = 'head-coach';
    const HEAD_COACH_ID         = 4;

    const COACH                 = 'coach';
    const COACH_ID              = 5;

    const QUIZMASTER            = 'quizmaster';
    const QUIZMASTER_ID         = 6;

    const GUARDIAN              = 'guardian';
    const GUARDIAN_ID           = 7;

    const ADMIN                 = 'admin';
    const ADMIN_ID              = 8;


    protected $guarded = ['id'];

    public function hasMailchimpInterest()
    {
        return $this->mailchimp_interest_id !== null;
    }
}
