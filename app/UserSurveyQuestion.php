<?php namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class UserSurveyQuestion extends Model
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
        return $this->hasMany(UserSurveyAnswer::class, 'question_id')->orderBy('created_at', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function users()
    {
        return $this->hasManyThrough(Us::class, UserSurvey::class)->orderBy('created_at', 'DESC');
    }
}
