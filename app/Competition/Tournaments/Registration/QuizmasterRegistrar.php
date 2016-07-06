<?php

namespace BibleBowl\Competition\Tournaments\Registration;

use BibleBowl\Group;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use BibleBowl\User;
use Illuminate\Mail\Message;
use Mail;

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
            'group_id'      => $group == null ? null : $group->id
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

        $tournamentQuizmaster->save();

        // if we didn't get preferences, email them for them
        if ($hasQuizzingPreferences == false) {
            Mail::queue(
                'emails.quizmaster-request-quizzing-preferences',
                [
                    'tournament'            => $tournament,
                    'group'                 => $group,
                    'tournamentQuizmaster'  => $tournamentQuizmaster
                ],
                function (Message $message) use ($tournament, $tournamentQuizmaster) {
                    $message->to($tournamentQuizmaster->email, $tournamentQuizmaster->full_name)
                        ->subject($tournament->name.' Quizzing Preferences');
                }
            );
        }

        return $tournamentQuizmaster;
    }
}