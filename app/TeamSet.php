<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;

/**
 * App\Season.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Player')
 *             ->withPivot('grade[] $players
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Season whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Season current()
 *
 * @property int $group_id
 * @property int $season_id
 * @property-read Group $group
 * @property-read Season $season
 * @property-read \Illuminate\Database\Eloquent\Collection|Team[] $teams
 *
 * @method static \Illuminate\Database\Query\Builder|\App\TeamSet whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TeamSet whereSeasonId($value)
 * @mixin \Eloquent
 *
 * @property int $tournament_id
 * @property-read \App\Tournament $tournament
 *
 * @method static \Illuminate\Database\Query\Builder|\App\TeamSet season(\App\Season $season)
 * @method static \Illuminate\Database\Query\Builder|\App\TeamSet whereTournamentId($value)
 */
class TeamSet extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function validationRules()
    {
        return [
            'name'  => 'required|max:64',
        ];
    }

    public function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function season() : BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function tournament() : BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function canBeEdited(User $user)
    {
        if ($this->registeredWithTournament() && $this->tournament->teamsAreLocked()) {
            return $this->tournament->canEditLockedTeams($user);
        }

        return true;
    }

    public function players() : Builder
    {
        $teamIds = $this->teams->modelKeys();

        return Player::whereHas('teams', function ($q) use ($teamIds) {
                $q->whereIn('id', $teamIds);
            })
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC');
    }

    public function unpaidPlayers() : Builder
    {
        if ($this->tournament_id == null) {
            throw new \RuntimeException("Teamsets without tournaments can't have unpaid players");
        }

        // without select.* it isn't sure which data to provide where tables share the same columns
        $tournamentId = $this->tournament_id;

        return $this->players()->select('players.*')
            ->leftJoin('tournament_players', function (JoinClause $join) use ($tournamentId) {
                $join->on('tournament_players.tournament_id', '=', DB::raw($tournamentId))
                    ->on('tournament_players.player_id', '=', 'players.id');
            })
            ->whereNull('tournament_players.receipt_id');
    }

    public function scopeSeason(Builder $query, Season $season) : Builder
    {
        return $query->where('season_id', $season->id);
    }

    public function registeredWithTournament() : bool
    {
        return $this->tournament_id != null;
    }

    public function teams() : HasMany
    {
        $seasonId = $this->season_id;

        return $this->hasMany(Team::class)
            // @TODO POATENTIAL HUGE RED FLAG FOR EXTERNAL GROUP INVITES
            // exclude players who are not registered with this group
            ->with([
                'players.seasons' => function (BelongsToMany $query) use ($seasonId) {
                    $query->where('season_id', $seasonId);
                    $query->withPivot('grade');
                },
            ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($teamSet) {
            foreach ($teamSet->teams as $team) {
                $team->delete();
            }
        });
    }
}
