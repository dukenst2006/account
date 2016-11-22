<?php

namespace BibleBowl\Competition\Tournaments\Quizmasters;

use BibleBowl\Group;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use BibleBowl\User;

class Registrar
{
    public function register(
        Tournament $tournament,
        array $attributes = null,
        User $user = null,
        Group $group = null,
        User $registeredBy = null
    ) : TournamentQuizmaster {
        $tournamentQuizmaster = app(TournamentQuizmaster::class, [[
            'tournament_id' => $tournament->id,
            'group_id'      => $group == null ? null : $group->id,
        ]]);

        if ($user != null) {
            $tournamentQuizmaster->user_id = $user->id;
        } elseif (isset($attributes['email'])) {
            // attempt to look up the user by email address
            $user = User::where('email', $attributes['email'])->first();
            if ($user != null && $user->exists()) {
                $tournamentQuizmaster->user_id = $user->id;
            }
        }

        // record if registered by another user
        if ($registeredBy != null && $tournamentQuizmaster->user_id != $registeredBy->id) {
            $tournamentQuizmaster->registered_by = $registeredBy->id;
        }

        // fields are set by head coaches when they register for quizmasters
        if (isset($attributes['first_name'])) {
            $tournamentQuizmaster->first_name = $attributes['first_name'];
        }
        if (isset($attributes['last_name'])) {
            $tournamentQuizmaster->last_name = $attributes['last_name'];
        }
        if (isset($attributes['email'])) {
            $tournamentQuizmaster->email = $attributes['email'];
        }
        if (isset($attributes['gender'])) {
            $tournamentQuizmaster->gender = $attributes['gender'];
        }
        if (isset($attributes['phone'])) {
            $tournamentQuizmaster->phone = $attributes['phone'];
        }

        if ($tournament->settings->shouldCollectShirtSizes()) {
            $tournamentQuizmaster->shirt_size = $attributes['shirt_size'];
        }

        if ($tournament->settings->shouldCollectQuizmasterPreferences()) {
            /** @var QuizzingPreferences $quizzingPreferences */
            $quizzingPreferences = $tournamentQuizmaster->quizzing_preferences;

            // if we have one, assume we have them all
            $hasQuizzingPreferences = isset($attributes['quizzed_at_tournament']);
            if ($hasQuizzingPreferences) {
                $quizzingPreferences->setQuizzedAtThisTournamentBefore($attributes['quizzed_at_tournament']);
                $quizzingPreferences->setTimesQuizzedAtThisTournament($attributes['times_quizzed_at_tournament']);
                $quizzingPreferences->setGamesQuizzedThisSeason($attributes['games_quizzed_this_season']);
                $quizzingPreferences->setQuizzingInterest($attributes['quizzing_interest']);
            }
            $tournamentQuizmaster->quizzing_preferences = $quizzingPreferences;
        }

        $tournamentQuizmaster->save();

        return $tournamentQuizmaster;
    }
}
