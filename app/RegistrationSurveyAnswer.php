<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

/**
 * BibleBowl\RegistrationSurveyAnswer
 *
 * @property int $id
 * @property int $question_id
 * @property string $answer
 * @property int $order
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \BibleBowl\RegistrationSurveyQuestion $question
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyAnswer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyAnswer whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\RegistrationSurveyAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegistrationSurveyAnswer extends Model
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
    public function question()
    {
        return $this->belongsTo(RegistrationSurveyQuestion::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, RegistrationSurvey::class, null, 'answer_id')->orderBy('created_at', 'DESC');
    }
}
