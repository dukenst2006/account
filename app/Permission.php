<?php namespace BibleBowl;

use Zizaco\Entrust\EntrustPermission;

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
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Permission whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Permission whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Permission whereUpdatedAt($value)
 */
class Permission extends EntrustPermission {
    const VIEW_REPORTS          = 'view-reports';
    const MANAGE_ROLES          = 'manage-roles';
    const CREATE_TOURNAMENTS    = 'create-tournaments';

    protected $guarded = ['id'];
}
