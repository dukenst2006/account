<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\RegistrationSurvey
 *
 * @property int $id
 * @property int $user_id
 * @property int $answer_id
 * @property string $other
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \BibleBowl\User $user
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurvey whereAnswerId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurvey whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurvey whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurvey whereOther($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurvey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurvey whereUserId($value)
 * @mixin \Eloquent
 */
class RegistrationSurvey extends Model
{
    /**
     * The attributes that are guarded against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
