<?php namespace BibleBowl;

use BibleBowl\Competition\Tournaments\Registration\QuizzingPreferences;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;

class TournamentQuizmaster extends Model
{

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        // assign a guid for each model
        static::creating(function ($tournamentQuizmaster) {
            $tournamentQuizmaster->guid = Uuid::uuid4();
            return true;
        });

        // Make sure the user has the quizmaster role
        // Using a saved event since the user could be assigned
        // after the initial creation
        static::saved(function ($tournamentQuizmaster) {
            $user = $tournamentQuizmaster->user;

            // if no user is linked, try to find one
            if (is_null($user)) {
                $user = User::where('email', $tournamentQuizmaster->email)->first();
            }
            
            if (!is_null($user)) {
                // label the user as a quizmaster
                if ($user->isNot(Role::QUIZMASTER)) {
                    $role = Role::where('name', Role::QUIZMASTER)->firstOrFail();
                    $user->assign($role);
                }

                // associate the user with the quizmaster
                if ($tournamentQuizmaster->user_id == null) {
                    $tournamentQuizmaster->update([
                        'user_id' => $user->id
                    ]);
                }
            }
        });
    }

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

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
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
    
    public function hasPaid()
    {
        return $this->receipt_id != null;
    }
}
