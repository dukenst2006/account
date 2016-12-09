<?php

namespace BibleBowl;

use BibleBowl\Shop\HasReceipts;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\JoinClause;

/**
 * BibleBowl\Season.
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\BibleBowl\Player')
 *             ->withPivot('grade[] $players
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Player[] $players
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|Tournament[] $tournaments
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Season current()
 *
 * @property int $team_set_id
 * @property-read Group $teamSet
 *
 * @method static \Illuminate\Database\Query\Builder|\BibleBowl\Team whereTeamSetId($value)
 */
class Team extends Model
{
    const REGISTRATION_SKU = 'TOURNAMENT_REG_TEAM';

    use HasReceipts;

    protected $guarded = ['id'];

    public function teamSet() : BelongsTo
    {
        return $this->belongsTo(TeamSet::class);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords(strtolower($name));
    }

    public function players() : BelongsToMany
    {
        $seasonId = $this->teamSet->season_id;

        return $this->belongsToMany(Player::class, 'team_player')
            ->withPivot('order')
            ->withTimestamps()
            ->with(['seasons' => function ($query) use ($seasonId) {
                $query->where('season_id', $seasonId);
            }])
            ->orderBy('team_player.order', 'ASC');
    }

    public function scopeWithEnoughPlayers(Builder $q, Tournament $tournament)
    {
        return $q->has('players', '>=', $tournament->settings->minimumPlayersPerTeam())
            ->has('players', '<=', $tournament->settings->maximumPlayersPerTeam());
    }

    public function scopeWithEnoughPaidPlayers(Builder $q, Tournament $tournament)
    {
        return $q->whereHas('players', function (Builder $q) use ($tournament) {
                $q->join('tournament_players', function (JoinClause $join) use ($tournament) {
                    $join->on('tournament_players.tournament_id', '=', DB::raw($tournament->id));
                    $join->on('tournament_players.player_id', '=', 'players.id');
                })
                ->whereNotNull('tournament_players.receipt_id');
            }, '>=', $tournament->settings->minimumPlayersPerTeam())
            ->whereHas('players', function (Builder $q) use ($tournament) {
                $q->join('tournament_players', function (JoinClause $join) use ($tournament) {
                    $join->on('tournament_players.tournament_id', '=', DB::raw($tournament->id));
                    $join->on('tournament_players.player_id', '=', 'players.id');
                })
                ->whereNotNull('tournament_players.receipt_id');
            }, '<=', $tournament->settings->maximumPlayersPerTeam());
    }

    public function scopeWithoutEnoughPlayers(Builder $q, Tournament $tournament)
    {
        return $q->has('players', '<', $tournament->settings->minimumPlayersPerTeam())
            ->orHas('players', '>', $tournament->settings->maximumPlayersPerTeam());
    }

    public function scopeWithEnoughQuizmastersInGroup(Builder $q, Tournament $tournament)
    {
        return $q->whereHas('teamSet', function (Builder $q) use ($tournament) {
            $q->whereHas('group', function (Builder $q) use ($tournament) {
                $q->where('tournament_id', $tournament->id);
                $q->has('tournamentQuizmasters', '>=', $tournament->settings->quizmastersToRequireByGroup());
            });
        });
    }

    public function scopeWithEnoughQuizmastersBasedOnTeamCount(Builder $q, Tournament $tournament)
    {
        $teamCount = $this->teamSet->teams()->withEnoughPlayers($tournament)->count();

        return $q->whereHas('teamSet', function (Builder $q) use ($tournament, $teamCount) {
            $q->whereHas('group', function (Builder $q) use ($tournament, $teamCount) {
                $q->where('tournament_id', $tournament->id);
                $q->has('tournamentQuizmasters', '>=', $tournament->numberOfQuizmastersRequiredByTeamCount($teamCount));
            });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($team) {
            $team->players()->sync([]);
        });
    }
}
