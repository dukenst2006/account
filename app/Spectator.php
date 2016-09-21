<?php

namespace BibleBowl;

use Illuminate\Database\Eloquent\Model;

class Spectator extends Model
{
    const REGISTRATION_ADULT_SKU = 'TOURNAMENT_REG_ADULT';
    const REGISTRATION_FAMILY_SKU = 'TOURNAMENT_REG_FAMILY';

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function minors()
    {
        return $this->hasMany(Minor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
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
    public function address()
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

    public function isFamily() : bool
    {
        if ($this->isFamily == null) {
            $this->isFamily = strlen($this->spouse_first_name) > 0 || $this->minors()->count() > 0;
        }

        return $this->isFamily;
    }

    public function sku()
    {
        if ($this->isFamily()) {
            return self::REGISTRATION_FAMILY_SKU;
        }

        return self::REGISTRATION_ADULT_SKU;
    }

    public function hasPaid()
    {
        return $this->receipt_id != null;
    }
}
