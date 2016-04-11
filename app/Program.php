<?php

namespace BibleBowl;

use BibleBowl\Presentation\Describer;
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
 * @property float $registration_fee
 * @property-read mixed $product_sku
 * @property boolean $min_grade
 * @property boolean $max_grade
 * @property-read mixed $sku
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereRegistrationFee($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereMinGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Program whereMaxGrade($value)
 * @mixin \Eloquent
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
    public function providers()
    {
        return $this->hasMany(Group::class);
    }

    public function scopeSlug(Builder $query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        // if this relation is updated, update Season too
        return $this->hasManyThrough(Player::class, Group::class, 'player_season')
            ->withPivot('season_id', 'group_id', 'grade', 'shirt_size')
            ->withTimestamps()
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC');
    }

    /**
     * @return string
     */
    public function getSkuAttribute()
    {
        return 'SEASON_REG_'.strtoupper($this->slug);
    }

    public function __toString()
    {
        $minGrade = Describer::describeGradeShort($this->min_grade);
        $maxGrade = Describer::describeGradeShort($this->max_grade);
        return $this->name.' ('.$minGrade.'-'.$maxGrade.' grades)';
    }
}
