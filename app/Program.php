<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    const BEGINNER = 1;
    const TEEN = 2;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function providers() {
        return $this->hasMany(Group::class);
    }

    public function scopeSlug(Builder $query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players() {
        // if this relation is updated, update Season too
        return $this->belongsToMany(Player::class, 'player_season')
            ->withPivot('season_id', 'group_id', 'grade', 'shirt_size')
            ->withTimestamps()
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC');
    }

    public function __toString()
    {
        return $this->name.' ('.$this->description.')';
    }
}
