<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

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
