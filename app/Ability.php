<?php namespace BibleBowl;

/**
 * BibleBowl\Permission
 *
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust.role')[] $roles
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Ability whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Ability whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Ability whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Ability whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Ability whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Ability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ability extends \Silber\Bouncer\Database\Ability
{
    const MANAGE_ROLES          = 'manage-roles';
    const MANAGE_ROSTER         = 'manage-roster';
    const MANAGE_TEAMS          = 'manage-teams';
    const MANAGE_USERS          = 'manage-users';
    const MANAGE_GROUPS         = 'manage-groups';
    // all players on a national level
    const MANAGE_PLAYERS        = 'manage-players';
    const REGISTER_PLAYERS      = 'register-players';
    const CREATE_TOURNAMENTS    = 'create-tournaments';
    const SWITCH_ACCOUNTS       = 'switch-accounts';
    const MANAGE_SETTINGS       = 'manage-settings';
    const VIEW_REPORTS          = 'view-reports';
}
