<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Role.
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model')[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.permission')[] $perms
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereUpdatedAt($value)
 *
 * @property string $mailchimp_interest_id
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereMailchimpInterestId($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ability[] $abilities
 *
 * @method static \Illuminate\Database\Query\Builder|\Silber\Bouncer\Database\Role whereAssignedTo($model, $keys = null)
 * @method static \Illuminate\Database\Query\Builder|\Silber\Bouncer\Database\Role whereCan($ability, $model = null)
 * @method static \Illuminate\Database\Query\Builder|\Silber\Bouncer\Database\Role whereCannot($ability, $model = null)
 * @mixin \Eloquent
 *
 * @property string $title
 * @property int $level
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Role editable()
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereTitle($value)
 */
class Role extends \Silber\Bouncer\Database\Role
{
    const BOARD_MEMBER = 'board-member';
    const LEAGUE_COORDINATOR = 'league-coordinator';
    const TOURNAMENT_COORDINATOR = 'tournament-coordinator';
    const HEAD_COACH = 'head-coach';
    const COACH = 'coach';
    const QUIZMASTER = 'quizmaster';
    const GUARDIAN = 'guardian';
    const ADMIN = 'admin';

    const EDITABLE_ROLES = [
        self::ADMIN,
        self::BOARD_MEMBER,
        self::QUIZMASTER,
        self::TOURNAMENT_COORDINATOR,
    ];

    public function getDisplayNameAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->name));
    }

    public function hasMailchimpInterest() : bool
    {
        return $this->mailchimp_interest_id !== null;
    }

    /**
     * Some roles can't be manually added to users because there's
     * automated functionality around the other roles and thus
     * features might break as certain data may be expected if
     * a user has these roles.
     */
    public function isEditable() : bool
    {
        return in_array($this->name, self::EDITABLE_ROLES);
    }

    public function scopeEditable(Builder $query)
    {
        return $query->whereIn('name', self::EDITABLE_ROLES);
    }
}
