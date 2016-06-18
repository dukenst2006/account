<?php namespace BibleBowl;

use BibleBowl\Competition\Tournaments\Registration\QuizzingPreferences;
use Illuminate\Database\Eloquent\Model;

class TournamentQuizmaster extends Model
{

    /**
     * The attributes that are not mass assignable.
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function getFirstNameAttribute()
    {
        if ($this->user_id != null) {
            return $this->user->first_name;
        }

        return $this->attributes['first_name'];
    }

    public function getLastNameAttribute()
    {
        if ($this->user_id != null) {
            return $this->user->last_name;
        }

        return $this->attributes['last_name'];
    }

    public function getEmailAttribute()
    {
        if ($this->user_id != null) {
            return $this->user->email;
        }

        return $this->attributes['email'];
    }

    public function getGenderAttribute()
    {
        if ($this->user_id != null) {
            return $this->user->gender;
        }

        return $this->attributes['gender'];
    }

    /**
     * @param $value
     * @return QuizzingPreferences
     */
    public function getQuizzingPreferencesAttribute($value)
    {
        if (is_null($value)) {
            return app(QuizzingPreferences::class);
        }

        return app(QuizzingPreferences::class, [$this->fromJson($value)]);
    }

    /**
     * @param QuizzingPreferences $value
     */
    public function setQuizzingPreferencesAttribute(QuizzingPreferences $value)
    {
        $this->attributes['quizzing_preferences'] = $value->toJson();
    }
}
