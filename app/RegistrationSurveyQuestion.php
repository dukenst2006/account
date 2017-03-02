<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RegistrationSurveyQuestion.
 *
 * @property int $id
 * @property string $question
 * @property int $order
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RegistrationSurveyAnswer[] $answers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RegistrationSurvey[] $surveys
 *
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyQuestion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyQuestion whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegistrationSurveyQuestion extends Model
{
    /**
     * The attributes that are guarded against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(RegistrationSurveyAnswer::class, 'question_id')->orderBy('created_at', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function surveys()
    {
        return $this->hasManyThrough(RegistrationSurvey::class, RegistrationSurveyAnswer::class, 'question_id', 'answer_id');
    }
}
