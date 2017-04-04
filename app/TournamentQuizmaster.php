<?php

namespace App;

use App\Competition\Tournaments\CanBeRegisteredByHeadCoach;
use App\Competition\Tournaments\Quizmasters\QuizzingPreferences;
use App\Shop\HasReceipts;
use App\Support\Scrubber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

/**
 * App\TournamentQuizmaster.
 *
 * @property int $id
 * @property string $guid
 * @property int $tournament_id
 * @property int $group_id
 * @property int $registered_by
 * @property int $receipt_id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int $phone
 * @property string $gender
 * @property string $shirt_size
 * @property QuizzingPreferences $quizzing_preferences
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read mixed $full_name
 * @property-read \App\Group $group
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Receipt $receipt
 * @property-read \App\User $registeredBy
 * @property-read \App\Tournament $tournament
 * @property-read \App\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster paid()
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster registeredByHeadCoach()
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster unpaid()
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereQuizzingPreferences($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereRegisteredBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TournamentQuizmaster whereUserId($value)
 * @mixin \Eloquent
 */
class TournamentQuizmaster extends Model
{
    const REGISTRATION_SKU = 'TOURNAMENT_REG_QUIZMASTER';

    use Notifiable;
    use HasReceipts;
    use CanBeRegisteredByHeadCoach;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $attributes = [
        'shirt_size'    => null,
        'user_id'       => null,
    ];

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
                if ($user->isNotA(Role::QUIZMASTER)) {
                    $role = Role::where('name', Role::QUIZMASTER)->firstOrFail();
                    $user->assign($role);
                }

                // associate the user with the quizmaster
                if ($tournamentQuizmaster->user_id == null) {
                    $tournamentQuizmaster->update([
                        'user_id' => $user->id,
                    ]);
                }
            }
        });
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receipt() : BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function hasGroup() : bool
    {
        return $this->group_id != null;
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

    public function setPhoneAttribute($attribute)
    {
        if ($this->user_id != null) {
            return $this->user->phone;
        }

        /** @var Scrubber $scrubber */
        $scrubber = app(Scrubber::class);
        $this->attributes['phone'] = $scrubber->phone($attribute);
    }

    /**
     * @param $value
     *
     * @return QuizzingPreferences
     */
    public function getQuizzingPreferencesAttribute($value)
    {
        if (is_null($value)) {
            return app(QuizzingPreferences::class);
        }

        return new QuizzingPreferences($this->fromJson($value));
    }

    public function setQuizzingPreferencesAttribute(QuizzingPreferences $value)
    {
        $this->attributes['quizzing_preferences'] = $value->toJson();
    }

    public function hasQuizzingPreferences() : bool
    {
        return $this->quizzing_preferences !== null && $this->quizzing_preferences != '';
    }

    /**
     * Default to null.
     */
    public function setShirtSizeAttribute($shirtSize)
    {
        if (strlen($shirtSize) > 0) {
            $this->attributes['shirt_size'] = $shirtSize;
        } else {
            $this->attributes['shirt_size'] = null;
        }
    }
}
