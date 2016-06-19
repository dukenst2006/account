<?php

namespace BibleBowl\Competition\Tournaments\Registration;

use BibleBowl\Group;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use BibleBowl\User;

class QuizmasterRegistrar
{
    public function register(
        Tournament $tournament,
        array $attributes = null,
        User $user = null,
        Group $group = null
    ) {
        $tournamentQuizmaster = app(TournamentQuizmaster::class, [[
            'tournament_id' => $tournament->id,
            'user_id'       => $user == null ? null : $user->id,
            'group_id'      => $group == null ? null : $group->id
        ]]);

        /** @var QuizzingPreferences $quizzingPreferences */
        $quizzingPreferences = $tournamentQuizmaster->quizzing_preferences;

        // if we have one, assume we ahve them all
        if (isset($attributes['quizzed_at_tournament'])) {
            $quizzingPreferences->setQuizzedAtThisTournamentBefore($attributes['quizzed_at_tournament']);
            $quizzingPreferences->setTimesQuizzedAtThisTournament($attributes['times_quizzed_at_tournament']);
            $quizzingPreferences->setGamesQuizzedThisSeason($attributes['games_quizzed_this_season']);
            $quizzingPreferences->setQuizzingInterest($attributes['quizzing_interest']);
        }
        $tournamentQuizmaster->quizzing_preferences = $quizzingPreferences;

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

        $tournamentQuizmaster->save();

        return $tournamentQuizmaster;
    }
}