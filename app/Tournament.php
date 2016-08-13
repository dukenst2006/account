<?php

namespace BibleBowl;

use BibleBowl\Presentation\Describer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use BibleBowl\Support\CanDeactivate;
use Illuminate\Support\Collection;

/**
 * BibleBowl\Tournament
 *
 * @property integer $id
 * @property string $guid
 * @property integer $season_id
 * @property string $name
 * @property boolean $active
 * @property string $start
 * @property string $end
 * @property string $registration_start
 * @property string $registration_end
 * @property string $url
 * @property integer $creator_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $events
 * @property-read Season $season
 * @property-read User $creator
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereSeasonId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereRegistrationStart($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereRegistrationEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereCreatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Tournament whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tournament extends Model
{
    use CanDeactivate;

    // The following participant types are required to
    // register for all tournaments
    const PARTICIPANTS_REQUIRED_TO_REGISTER = [
        ParticipantType::PLAYER,
        ParticipantType::TEAM
    ];

    protected $attributes = [
        'lock_teams'    => null
    ];

    protected $guarded = ['id'];

    protected $casts = ['active'];

    private $participantTypesWithOnSiteRegistrationCache = null;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participantFees()
    {
        return $this->hasMany(ParticipantFee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spectators()
    {
        return $this->hasMany(Spectator::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournamentQuizmasters()
    {
        return $this->hasMany(TournamentQuizmaster::class);
    }

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = str_slug($slug);
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

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $end
     */
    public function setLockTeamsAttribute($lock_teams)
    {
        if (is_null($lock_teams) || strlen($lock_teams) == 0) {
            $this->attributes['lock_teams'] = null;
        } else {
            $this->attributes['lock_teams'] = Carbon::createFromFormat('m/d/Y', $lock_teams);
        }
    }

    /**
     * Provide end as a Carbon object
     *
     * @param $end
     *
     * @return static
     */
    public function getLockTeamsAttribute($lock_teamsed)
    {
        if (is_null($lock_teamsed)) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d', $lock_teamsed);
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving
     *
     * @param $end
     */
    public function setEarlybirdEndsAttribute($earlybird_ends)
    {
        if (is_null($earlybird_ends) || strlen($earlybird_ends) == 0) {
            $this->attributes['earlybird_ends'] = null;
        } else {
            $this->attributes['earlybird_ends'] = Carbon::createFromFormat('m/d/Y', $earlybird_ends);
        }
    }

    /**
     * Provide end as a Carbon object
     *
     * @return static
     */
    public function getEarlybirdEndsAttribute($earlybird_ends)
    {
        if (is_null($earlybird_ends)) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d', $earlybird_ends);
    }

    public function teamsWillLock() : bool
    {
        return is_null($this->lock_teams) == false;
    }

    public function teamsAreLocked() : bool
    {
        return $this->teamsWillLock() && Carbon::now()->gte($this->lock_teams);
    }

    public function hasEarlyBirdRegistration() : bool
    {
        return is_null($this->earlybird_ends) == false;
    }

    /**
     * Determine if registration is currently open
     */
    public function isRegistrationOpen() : bool
    {
        $now = Carbon::now();
        return $now->gte($this->registration_start) && $now->lte($this->registration_end);
    }

    public function isRegistrationClosed() : bool
    {
        return $this->isRegistrationOpen() === false;
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

    /**
     * Get the fee for a ParticipantType
     */
    public function fee(ParticipantType $participantType)
    {
        /** @var ParticipantFee $participantFee */
        $participantFee = $this->participantFees()
            ->where('participant_type_id', $participantType->id)
            ->first();
        if ($this->hasEarlyBirdRegistration() && $participantFee->earlybird_fee != null && Carbon::now()->lte($this->earlybird_ends)) {
            return $participantFee->earlybird_free;
        }

        return $participantFee->fee;
    }

    public function registrationIsEnabled(int $participantTypeId)
    {
        return $this->participantFees->filter(function ($fee) use ($participantTypeId) {
            return $fee->participant_type_id == $participantTypeId && $fee->requires_registration;
        })->count() > 0;
    }

    public function allowsOnSiteRegistration() : bool
    {
        return $this->participantTypesWithOnSiteRegistration()->count() > 0;
    }

    public function participantTypesWithOnSiteRegistration() : Collection
    {
        if ($this->participantTypesWithOnSiteRegistrationCache == null) {
            $tournamentId = $this->id;
            $participantTypes = ParticipantType::whereHas('participantFee', function (Builder $q) use($tournamentId) {
                $q->whereNotNull('onsite_fee')
                    ->where('tournament_id', $tournamentId);
            })->get();

            $this->participantTypesWithOnSiteRegistrationCache = $participantTypes;
        }

        return $this->participantTypesWithOnSiteRegistrationCache;
    }

    public function isRegisteredAsQuizmaster(User $user)
    {
        return $this->tournamentQuizmasters()->where('user_id', $user->id)->count() > 0;
    }

    public function isRegisteredAsSpectator(User $user)
    {
        return $this->spectators()->where('user_id', $user->id)->count() > 0;
    }

    /**
     * Fetch tournaments that are
     */
    public function scopeVisible(Builder $query, $programId, Season $season)
    {
        return $query->where('program_id', $programId)
            ->where('season_id', $season->id)
            ->whereDate('registration_start', '>', Carbon::now())
            ->orderBy('start', 'ASC');
    }

}
