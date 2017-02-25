<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\GroupType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Group[] $groups
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\GroupType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\GroupType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\GroupType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\GroupType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GroupType extends Model
{
    const HOMESCHOOL = 1;
    const CHURCH = 2;
    const OTHER = 3;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
