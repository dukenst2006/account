<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\Program
 *
 * @property integer $id
 * @property string $name
 * @property string $abbreviation
 * @property string $slug
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereAbbreviation($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program slug($slug)
 */
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
