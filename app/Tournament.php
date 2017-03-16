<?php

namespace App;

use App\Competition\Tournaments\Groups\Registration;
use App\Competition\Tournaments\Settings;
use App\Presentation\Describer;
use App\Support\CanDeactivate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * App\Tournament.
 *
 * @property int $id
 * @property string $guid
 * @property int $season_id
 * @property string $name
 * @property bool $active
 * @property string $start
 * @property string $end
 * @property string $registration_start
 * @property string $registration_end
 * @property string $url
 * @property Settings $settings
 * @property int $creator_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $events
 * @property-read Season $season
 * @property-read User $creator
 *
 * @method static \Illuminate\Database\Query\Builder|Tournament whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereSeasonId($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereEnd($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereRegistrationStart($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereRegistrationEnd($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereCreatorId($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @property int $tournament_type_id
 * @property string $inactive
 * @property string $slug
 * @property int $program_id
 * @property string $details
 * @property string $fees
 * @property bool $max_teams
 * @property static $lock_teams
 * @property static $earlybird_ends
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Minor[] $minors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ParticipantFee[] $participantFees
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Player[] $players
 * @property-read \App\Program $program
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receipt[] $receipts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Spectator[] $spectators
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TeamSet[] $teamSets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Team[] $teams
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TournamentQuizmaster[] $tournamentQuizmasters
 * @property-read \App\TournamentType $type
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament active()
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament inactive()
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament visible(\App\Season $season, $programId = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereDetails($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereEarlybirdEnds($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereFees($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereInactive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereLockTeams($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereMaxTeams($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereProgramId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereSettings($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Tournament whereTournamentTypeId($value)
 */
class Tournament extends Model
{
    use CanDeactivate;

    // The following participant types are required to
    // register for all tournaments
    const PARTICIPANTS_REQUIRED_TO_REGISTER = [
        ParticipantType::PLAYER,
        ParticipantType::TEAM,
    ];

    protected $attributes = [
        'lock_teams'            => null,
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'active',
        'settings' => Settings::class,
    ];

    protected $teamSetCache = [];

    private $isRegistrationOpenCache = null;

    private $participantTypesWithOnSiteRegistrationCache = null;

    public function events() : HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function individualEvents() : HasMany
    {
        return $this->events()->byParticipantType(ParticipantType::PLAYER);
    }

    public function teamEvents() : HasMany
    {
        return $this->events()->byParticipantType(ParticipantType::TEAM);
    }

    public function participantFees() : HasMany
    {
        return $this->hasMany(ParticipantFee::class);
    }

    public function spectators() : HasMany
    {
        return $this->hasMany(Spectator::class);
    }

    public function eligibleSpectators() : Builder
    {
        $q = $this->spectators();

        if ($this->hasFee(ParticipantType::ADULT) && $this->hasFee(ParticipantType::FAMILY)) {
            return $q->paid()->getQuery();
        }

        if ($this->hasFee(ParticipantType::ADULT) && $this->hasFee(ParticipantType::FAMILY) === false) {
            return $q->paid()->adults()->getQuery();
        }

        if ($this->hasFee(ParticipantType::FAMILY) && $this->hasFee(ParticipantType::ADULT) === false) {
            return $q->paid()->families()->getQuery();
        }

        return $q->getQuery();
    }

    public function eligibleMinors() : Builder
    {
        $q = $this->minors();

        if ($this->hasFee(ParticipantType::FAMILY)) {
            return $q->whereHas('spectator', function (Builder $q) {
                $q->paid()->families();
            })->getQuery();
        }

        return $q->getQuery();
    }

    public function minors() : HasManyThrough
    {
        return $this->hasManyThrough(Minor::class, Spectator::class);
    }

    public function teamSets() : HasMany
    {
        return $this->hasMany(TeamSet::class);
    }

    public function receipts() : HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function eligibleTeams() : HasManyThrough
    {
        if ($this->hasFee(ParticipantType::PLAYER)) {
            $teams = $this->teams()->withEnoughPaidPlayers($this);
        } else {
            $teams = $this->teams()->withEnoughPlayers($this);
        }

        if ($this->hasFee(ParticipantType::TEAM)) {
            $teams->paid();
        }

        if ($this->settings->shouldRequireQuizmastersByGroup()) {
            $teams->withEnoughQuizmastersInGroup($this);
        } elseif ($this->settings->shouldRequireQuizmastersByTeamCount()) {
            $teams->withEnoughQuizmastersBasedOnTeamCount($this);
        }

        return $teams;
    }

    public function teams() : HasManyThrough
    {
        return $this->hasManyThrough(Team::class, TeamSet::class);
    }

    public function season() : BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program() : BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(TournamentType::class);
    }

    public function eligiblePlayers() : Builder
    {
        // if there's a fee, we'll assume any team-related checks
        // (e.g. - number of players per team) were performed
        // before players were paid for
        if ($this->hasFee(ParticipantType::PLAYER)) {
            return $this->players()->whereNotNull('tournament_players.receipt_id')->getQuery();
        }

        $tournament = $this;

        return Player::whereHas('teams', function (Builder $q) use ($tournament) {
            $q->whereHas('teamSet', function (Builder $q) use ($tournament) {
                $q->where('tournament_id', $tournament->id);
            })
            ->withEnoughPlayers($this);
        });
    }

    public function players() : BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'tournament_players')
            ->withPivot('receipt_id')
            ->withTimestamps();
    }

    public function tournamentQuizmasters(Group $group = null) : HasMany
    {
        if ($group instanceof Group) {
            return $this->hasMany(TournamentQuizmaster::class)->where('group_id', $group->id);
        }

        return $this->hasMany(TournamentQuizmaster::class);
    }

    public function eligibleQuizmasters() : HasMany
    {
        if ($this->hasFee(ParticipantType::QUIZMASTER)) {
            return $this->tournamentQuizmasters()->paid();
        }

        return $this->tournamentQuizmasters();
    }

    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = str_slug($slug);
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving.
     *
     * @param $start
     */
    public function setStartAttribute($start)
    {
        $this->attributes['start'] = Carbon::createFromFormat('m/d/Y', $start);
    }

    /**
     * Provide start as a Carbon object.
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
     * Convert from m/d/Y to a Carbon object for saving.
     *
     * @param $end
     */
    public function setEndAttribute($end)
    {
        $this->attributes['end'] = Carbon::createFromFormat('m/d/Y', $end);
    }

    /**
     * Provide end as a Carbon object.
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
     * Convert from m/d/Y to a Carbon object for saving.
     *
     * @param $registration_start
     */
    public function setRegistrationStartAttribute($registration_start)
    {
        if ($registration_start instanceof Carbon) {
            $this->attributes['registration_start'] = $registration_start;
        } else {
            $this->attributes['registration_start'] = Carbon::createFromFormat('m/d/Y', $registration_start);
        }
    }

    /**
     * Provide registration start as a Carbon object.
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
     * Convert from m/d/Y to a Carbon object for saving.
     *
     * @param $registration_end
     */
    public function setRegistrationEndAttribute($registration_end)
    {
        if ($registration_end instanceof Carbon) {
            $this->attributes['registration_end'] = $registration_end;
        } else {
            $this->attributes['registration_end'] = Carbon::createFromFormat('m/d/Y', $registration_end);
        }
    }

    /**
     * Provide registration end as a Carbon object.
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
     * @param $value
     *
     * @return Settings
     */
    public function getSettingsAttribute($value)
    {
        if (is_null($value)) {
            return app(Settings::class);
        }

        return new Settings($this->fromJson($value));
    }

    /**
     * @param Settings $value
     */
    public function setSettingsAttribute(Settings $value)
    {
        $this->attributes['settings'] = $value->toJson();
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving.
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
     * Provide end as a Carbon object.
     *
     * @param $end
     *
     * @return static
     */
    public function getLockTeamsAttribute($lock_teamsed)
    {
        if (is_null($lock_teamsed)) {
            return;
        }

        return Carbon::createFromFormat('Y-m-d', $lock_teamsed);
    }

    public function teamSet(Group $group) : TeamSet
    {
        // cache this since we access it on several pages more than once
        if (!array_key_exists($group->id, $this->teamSetCache)) {
            $this->teamSetCache[$group->id] = $group->teamSet($this);
        }

        return $this->teamSetCache[$group->id];
    }

    /**
     * Convert from m/d/Y to a Carbon object for saving.
     *
     * @param $end
     */
    public function setEarlybirdEndsAttribute($earlybird_ends)
    {
        if (is_null($earlybird_ends) || strlen($earlybird_ends) == 0) {
            $this->attributes['earlybird_ends'] = null;
        } elseif ($earlybird_ends instanceof Carbon) {
            $this->attributes['earlybird_ends'] = $earlybird_ends;
        } else {
            $this->attributes['earlybird_ends'] = Carbon::createFromFormat('m/d/Y', $earlybird_ends);
        }
    }

    /**
     * Provide end as a Carbon object.
     *
     * @return static
     */
    public function getEarlybirdEndsAttribute($earlybird_ends)
    {
        if (is_null($earlybird_ends)) {
            return;
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

    public function canEditLockedTeams(User $user) : bool
    {
        return $this->isCreator($user) || $user->isA(Role::ADMIN);
    }

    public function hasEarlyBirdRegistration() : bool
    {
        return is_null($this->earlybird_ends) == false;
    }

    /**
     * Determine if registration is currently open.
     */
    public function isRegistrationOpen() : bool
    {
        if ($this->isRegistrationOpenCache == null) {
            $now = Carbon::now();

            $this->isRegistrationOpenCache = $now->gte($this->registration_start) && $now->lte($this->registration_end);
        }

        return $this->isRegistrationOpenCache;
    }

    public function isRegistrationClosed() : bool
    {
        return $this->isRegistrationOpen() === false;
    }

    /**
     * Get date span.
     */
    public function dateSpan()
    {
        return Describer::dateSpan($this->start, $this->end);
    }

    /**
     * Get date span.
     */
    public function registrationDateSpan()
    {
        return Describer::dateSpan($this->registration_start, $this->registration_end);
    }

    public function hasFee(int $participantTypeId) : bool
    {
        return $this->fee($participantTypeId) > 0;
    }

    public function hasAnyParticipantFees() : bool
    {
        return $this->participantFees->filter(function (ParticipantFee $fee) {
            return $fee->hasAnyFees();
        })->count() > 0;
    }

    public function hasEarlyBirdRegistrationFee(int $participantTypeId) : bool
    {
        if ($this->hasEarlyBirdRegistration()) {
            /** @var ParticipantFee $participantFee */
            $participantFee = $this->participantFees
                ->where('participant_type_id', $participantTypeId)
                ->first();

            return $participantFee->hasEarlybirdFee() && $this->inEarlyBirdWindow();
        }

        return false;
    }

    public function inEarlyBirdWindow() : bool
    {
        return Carbon::now()->lte($this->earlybird_ends->endOfDay());
    }

    /**
     * Get the fee for a ParticipantType.
     */
    public function fee(int $participantTypeId)
    {
        /** @var ParticipantFee $participantFee */
        $participantFee = $this->participantFees
            ->where('participant_type_id', $participantTypeId)
            ->first();

        if ($this->hasEarlyBirdRegistrationFee($participantTypeId)) {
            return $participantFee->earlybird_fee;
        }

        return $participantFee->fee;
    }

    public function registrationIsEnabled(int $participantTypeId) : bool
    {
        return $this->participantFees->filter(function ($fee) use ($participantTypeId) {
            return $fee->participant_type_id == $participantTypeId && $fee->requiresRegistration();
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
            $participantTypes = ParticipantType::whereHas('participantFee', function (Builder $q) use ($tournamentId) {
                $q->whereNotNull('onsite_fee')
                    ->where('tournament_id', $tournamentId);
            })->get();

            $this->participantTypesWithOnSiteRegistrationCache = $participantTypes;
        }

        return $this->participantTypesWithOnSiteRegistrationCache;
    }

    public function isRegisteredAsQuizmaster(User $user) : bool
    {
        return $this->tournamentQuizmasters()->where('user_id', $user->id)->count() > 0;
    }

    public function isRegisteredAsSpectator(User $user) : bool
    {
        return $this->spectators()->where('user_id', $user->id)->count() > 0;
    }

    public function isCreator(User $user) : bool
    {
        return $this->creator_id == $user->id;
    }

    /**
     * Number of teams spots left to fill.
     */
    public function teamSpotsLeft() : int
    {
        return $this->max_teams - $this->teams()->count();
    }

    /**
     * Fetch tournaments that are visible to the public.
     */
    public function scopeVisible(Builder $query, Season $season, int $programId = null)
    {
        $query->where('season_id', $season->id);

        if ($programId != null) {
            $query->where('program_id', $programId);
        }

        $query
            ->whereDate('registration_start', '<=', Carbon::now())
            ->orderBy('start', 'ASC');

        return $query;
    }

    public function shouldWarnAboutTeamLocking() : bool
    {
        return $this->lock_teams->lt(\Carbon\Carbon::now()->addDays(7));
    }

    public function numberOfQuizmastersRequiredByTeamCount(int $teamCount) : int
    {
        $quizmastersRequired = floor(($this->settings->quizmastersToRequireByTeamCount() * $teamCount) / $this->settings->teamCountToRequireQuizmastersBy());

        if ($quizmastersRequired < $this->settings->quizmastersToRequireByTeamCount()) {
            return $this->settings->quizmastersToRequireByTeamCount();
        }

        return $quizmastersRequired;
    }

    public function eligibleRegistrationWithOutstandingFees(Group $group) : Registration
    {
        $groupRegistration = new Registration();
        $teamSet = $this->teamSet($group);

        $groupRegistration->setTournament($this);

        if ($this->hasFee(ParticipantType::TEAM)) {
            $groupRegistration->setTeamIds($teamSet->teams()->withEnoughPlayers($this)->unpaid()->get()->modelKeys());
        }

        if ($this->hasFee(ParticipantType::PLAYER)) {
            $groupRegistration->setPlayerIds($teamSet->unpaidPlayers()->get()->modelKeys());
        }

        if ($this->hasFee(ParticipantType::QUIZMASTER)) {
            $groupRegistration->setQuizmasterIds($this->tournamentQuizmasters()->registeredByHeadCoach()->unpaid()->where('group_id', $group->id)->get()->modelKeys());
        }

        if ($this->hasFee(ParticipantType::ADULT)) {
            $groupRegistration->setAdultIds($this->spectators()->registeredByHeadCoach()->adults()->unpaid()->group($group)->get()->modelKeys());
        }

        if ($this->hasFee(ParticipantType::FAMILY)) {
            $groupRegistration->setFamilyIds($this->spectators()->registeredByHeadCoach()->families()->unpaid()->group($group)->get()->modelKeys());
        }

        foreach ($this->events()->byParticipantType(ParticipantType::PLAYER)->requiringFees()->get() as $event) {
            $unpaidPlayerIds = $event->unpaidPlayers()->onTeamSet($teamSet)->get()->modelKeys();
            if (count($unpaidPlayerIds) > 0) {
                $groupRegistration->addEventParticipants($event->id, $unpaidPlayerIds);
            }
        }

        return $groupRegistration;
    }
}
