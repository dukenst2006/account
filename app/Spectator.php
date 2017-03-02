<?php

namespace BibleBowl;

use BibleBowl\Competition\Tournaments\CanBeRegisteredByHeadCoach;
use BibleBowl\Shop\HasReceipts;
use BibleBowl\Support\Scrubber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Uuid;

/**
 * BibleBowl\Spectator.
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
 * @property-read \BibleBowl\Address $address
 * @property-read mixed $full_name
 * @property-read mixed $participant_type
 * @property-read \BibleBowl\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Minor[] $minors
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \BibleBowl\Receipt $receipt
 * @property-read \BibleBowl\User $registeredBy
 * @property-read \BibleBowl\Tournament $tournament
 * @property-read \BibleBowl\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator adults()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator families()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator group(\BibleBowl\Group $group)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator paid()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator registeredByHeadCoach()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator unpaid()
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereRegisteredBy($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereSpouseFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereSpouseGender($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereSpouseShirtSize($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereTournamentId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Spectator whereUserId($value)
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
