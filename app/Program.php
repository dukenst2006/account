<?php

namespace App;

use App\Presentation\Describer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Program.
 *
 * @property int $id
 * @property string $name
 * @property string $abbreviation
 * @property string $slug
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $providers
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereAbbreviation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program slug($slug)
 *
 * @property float $registration_fee
 * @property-read mixed $product_sku
 * @property bool $min_grade
 * @property bool $max_grade
 * @property-read mixed $sku
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereRegistrationFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereMinGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Program whereMaxGrade($value)
 * @mixin \Eloquent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tournament[] $tournaments
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

    public function providers() : HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function groups() : HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function tournaments() : HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    public function scopeSlug(Builder $query, $slug)
    {
        return $query->where('slug', $slug);
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
        // Beginner allows 2nd graders to register, but lets still publicize as being 3rd grade
        $minGrade = $this->min_grade;
        if ($this->min_grade < 3) {
            $minGrade = 3;
        }
        $minGrade = Describer::describeGradeShort($minGrade);
        $maxGrade = Describer::describeGradeShort($this->max_grade);

        return $this->name.' ('.$minGrade.'-'.$maxGrade.' grades)';
    }
}
