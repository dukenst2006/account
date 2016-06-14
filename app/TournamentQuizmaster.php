<?php namespace BibleBowl;

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

    public function geGenderAttribute()
    {
        if ($this->user_id != null) {
            return $this->user->gender;
        }

        return $this->attributes['gender'];
    }
}
