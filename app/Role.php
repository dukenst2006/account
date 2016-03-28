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
class Role extends \Silber\Bouncer\Database\Role
{
    const BOARD_MEMBER              = 'board-member';
    const LEAGUE_COORDINATOR        = 'league-coordinator';
    //const TOURNAMENT_COORDINATOR    = 'tournament-coordinator';
    const HEAD_COACH                = 'head-coach';
    const COACH                     = 'coach';
    const QUIZMASTER                = 'quizmaster';
    const GUARDIAN                  = 'guardian';
    const ADMIN                     = 'admin';

    public function hasMailchimpInterest()
    {
        return $this->mailchimp_interest_id !== null;
    }
}
