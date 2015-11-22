<?php

namespace BibleBowl;

use BibleBowl\Presentation\Describer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Rhumsaa\Uuid\Uuid;

class Tournament extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $attributes = [
        'active' => self::INACTIVE
    ];

    protected $guarded = ['id', 'guid'];

    protected $casts = ['active'];

    public static function boot()
    {
        parent::boot();

        //assign a guid for each tournament
        static::creating(function ($tournament) {
            $tournament->guid = Uuid::uuid4();
            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events() {
        return $this->hasMany(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function season() {
        return $this->belongsTo(Season::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator() {
        return $this->belongsTo(User::class);
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $start
     */
    public function setStartAttribute($start)
    {
        $this->attributes['start'] = Carbon::createFromFormat('m/d/Y', $start);
    }

    /**
     * Provide start as a Carbon object
     *
     * @param $start
     *
     * @return static
     */
    public function getStartAttribute($start)
    {
        return Carbon::createFromFormat('Y-m-d', $start);
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $end
     */
    public function setEndAttribute($end)
    {
        $this->attributes['end'] = Carbon::createFromFormat('m/d/Y', $end);
    }

    /**
     * Provide end as a Carbon object
     *
     * @param $end
     *
     * @return static
     */
    public function getEndAttribute($end)
    {
        return Carbon::createFromFormat('Y-m-d', $end);
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $registration_start
     */
    public function setRegistrationStartAttribute($registration_start)
    {
        $this->attributes['registration_start'] = Carbon::createFromFormat('m/d/Y', $registration_start);
    }

    /**
     * Provide registration start as a Carbon object
     *
     * @param $registration_start
     *
     * @return static
     */
    public function getRegistrationStartAttribute($registration_start)
    {
        return Carbon::createFromFormat('Y-m-d', $registration_start);
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $registration_end
     */
    public function setRegistrationEndAttribute($registration_end)
    {
        $this->attributes['registration_end'] = Carbon::createFromFormat('m/d/Y', $registration_end);
    }

    /**
     * Provide registration end as a Carbon object
     *
     * @param $registration_end
     *
     * @return static
     */
    public function getRegistrationEndAttribute($registration_end)
    {
        return Carbon::createFromFormat('Y-m-d', $registration_end);
    }

    public function isRegistrationOpen()
    {
        $now = Carbon::now();
        return $now->gte($this->registration_start) && $now->lte($this->registration_end);
    }

    /**
     * Get date span
     */
    public function dateSpan()
    {
        return Describer::dateSpan($this->start, $this->end);
    }

    /**
     * Get date span
     */
    public function registrationDateSpan()
    {
        return Describer::dateSpan($this->registration_start, $this->registration_end);
    }
}
