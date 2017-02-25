<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\RegistrationSurveyQuestion.
 *
 * @property int $id
 * @property string $question
 * @property int $order
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\RegistrationSurveyAnswer[] $answers
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\RegistrationSurvey[] $surveys
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyQuestion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyQuestion whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyQuestion whereUpdatedAt($value)
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
