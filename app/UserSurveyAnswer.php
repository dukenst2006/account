<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class UserSurveyAnswer extends Model
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
        return $this->belongsTo(UserSurveyQuestion::class, 'question_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, UserSurvey::class, null, 'answer_id')->orderBy('created_at', 'DESC');
    }
}
