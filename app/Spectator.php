<?php

namespace App;

use App\Competition\Tournaments\CanBeRegisteredByHeadCoach;
use App\Shop\HasReceipts;
use App\Support\Scrubber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

/**
 * App\Spectator.
 *
 * @property int $id
 * @property string $guid
 * @property int $tournament_id
 * @property int $group_id
 * @property int $registered_by
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int $phone
 * @property string $gender
 * @property string $shirt_size
 * @property string $spouse_first_name
 * @property string $spouse_gender
 * @property string $spouse_shirt_size
 * @property int $address_id
 * @property int $receipt_id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Address $address
 * @property-read mixed $full_name
 * @property-read mixed $participant_type
 * @property-read \App\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Minor[] $minors
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Receipt $receipt
 * @property-read \App\User $registeredBy
 * @property-read \App\Tournament $tournament
 * @property-read \App\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator adults()
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator families()
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator group(\App\Group $group)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator paid()
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator registeredByHeadCoach()
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator unpaid()
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereRegisteredBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereSpouseFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereSpouseGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereSpouseShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Spectator whereUserId($value)
 * @mixin \Eloquent
 */
class Spectator extends Model
{
    const REGISTRATION_ADULT_SKU = 'TOURNAMENT_REG_ADULT';
    const REGISTRATION_FAMILY_SKU = 'TOURNAMENT_REG_FAMILY';

    use Notifiable;
    use HasReceipts;
    use CanBeRegisteredByHeadCoach;

    private $isFamily = null;

    protected $attributes = [
        'first_name'            => null,
        'last_name'             => null,
        'email'                 => null,
        'gender'                => null,
        'shirt_size'            => null,
        'spouse_first_name'     => null,
        'spouse_gender'         => null,
        'spouse_shirt_size'     => null,
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tournament_spectators';

    /**
     * The attributes that are guarded against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        // assign a guid for each model
        static::creating(function ($spectator) {
            $spectator->guid = Uuid::uuid4();

            return true;
        });
    }

    public function minors() : HasMany
    {
        return $this->hasMany(Minor::class);
    }

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function receipt() : BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function getParticipantTypeAttribute() : ParticipantType
    {
        if ($this->isFamily()) {
            return ParticipantType::find(ParticipantType::FAMILY);
        }

        return ParticipantType::find(ParticipantType::ADULT);
    }

    public function scopeGroup(Builder $q, Group $group) : Builder
    {
        return $q->where('group_id', $group->id);
    }

    public function scopeFamilies(Builder $q) : Builder
    {
        return $q->whereNotNull('spouse_first_name')->orHas('minors', '>', 0);
    }

    public function scopeAdults(Builder $q) : Builder
    {
        return $q->whereNull('spouse_first_name')->has('minors', '=', 0);
    }

    public function getFirstNameAttribute()
    {
        if ($this->attributes['first_name'] == null && $this->user_id != null) {
            return $this->user->first_name;
        }

        return $this->attributes['first_name'];
    }

    public function getLastNameAttribute()
    {
        if ($this->attributes['last_name'] == null && $this->user_id != null) {
            return $this->user->last_name;
        }

        return $this->attributes['last_name'];
    }

    public function getEmailAttribute()
    {
        if ($this->attributes['email'] == null && $this->user_id != null) {
            return $this->user->email;
        }

        return $this->attributes['email'];
    }

    public function getGenderAttribute()
    {
        if ($this->attributes['gender'] == null && $this->user_id != null) {
            return $this->user->gender;
        }

        return $this->attributes['gender'];
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getPhoneAttribute()
    {
        if ($this->attributes['phone'] == null && $this->user_id != null) {
            return $this->user->phone;
        }

        return $this->attributes['phone'];
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

    public function getAddressIdAttribute()
    {
        if ($this->attributes['address_id'] == null && $this->user_id != null) {
            return $this->user->primary_address_id;
        }

        return $this->attributes['address_id'];
    }

    public function isFamily() : bool
    {
        if ($this->isFamily == null) {
            $this->isFamily = strlen($this->spouse_first_name) > 0 || $this->minors()->count() > 0;
        }

        return $this->isFamily;
    }

    public function type() : string
    {
        if ($this->isFamily()) {
            return 'Family';
        }

        return 'Adult';
    }

    public function isAdult() : bool
    {
        return !$this->isFamily();
    }

    public function hasSpouse() : bool
    {
        return strlen($this->spouse_first_name) > 0;
    }
}
