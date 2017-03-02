<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\RegistrationSurveyAnswer.
 *
 * @property int $id
 * @property int $question_id
 * @property string $answer
 * @property int $order
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\RegistrationSurveyQuestion $question
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 *
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyAnswer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyAnswer whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RegistrationSurveyAnswer whereUpdatedAt($value)
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

    public function question() : BelongsTo
    {
        return $this->belongsTo(RegistrationSurveyQuestion::class, 'question_id');
    }

    public function users() : HasMany
    {
        return $this->hasManyThrough(User::class, RegistrationSurvey::class, null, 'answer_id')->orderBy('created_at', 'DESC');
    }
}
