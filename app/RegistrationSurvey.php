<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RegistrationSurvey.
 *
 * @property int $id
 * @property int $user_id
 * @property int $answer_id
 * @property string $other
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurvey whereAnswerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurvey whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurvey whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurvey whereOther($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurvey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurvey whereUserId($value)
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
