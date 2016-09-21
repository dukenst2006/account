<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

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
