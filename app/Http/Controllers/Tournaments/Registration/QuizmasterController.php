<?php namespace BibleBowl\Http\Controllers\Tournaments\Registration;

use Auth;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistration;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\Tournament\Registration\StandaloneQuizmasterRegistrationRequest;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistrationPaymentReceived;
use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use BibleBowl\Competition\Tournaments\Registration\QuizzingPreferences;
use Cart;
use Session;

class QuizmasterController extends Controller
{

    public function getRegistration($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        $participantType = ParticipantType::findOrFail(ParticipantType::QUIZMASTER);

        // determine if the head coach is registering this quizmaster
        if (Session::group() == null) {
            $view = 'tournaments.registration.standalone-quizmaster';
            $availableGroups = Group::active()
                ->byProgram($tournament->program_id)
                ->orderBy('name')
                ->with('meetingAddress')
                ->get();

            $groups = ['' => ''];
            foreach ($availableGroups as $group) {
                $groups[$group->id] = $group->name.' - '.$group->meetingAddress->city.', '.$group->meetingAddress->state;
            }
        } else {
            $groups = null;
            $view = 'tournaments.registration.headcoach-quizmaster';
        }

        $fee = $tournament->fee($participantType);
        return view($view, [
            'tournament'    => $tournament,
            'groups'        => $groups,
            'hasFee'        => $fee > 0,
            'fee'           => $fee
        ]);
    }

    /**
     * Standalone registration is a Quizmaster that is registering
     * themselves, not the Head Coach registering on their behalf.
     */
    public function postStandaloneRegistration(
        StandaloneQuizmasterRegistrationRequest $request,
        $slug,
        TournamentQuizmaster $tournamentQuizmaster,
        QuizmasterRegistrationPaymentReceived $quizmasterRegistrationPaymentReceived
    ) {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        $participantType = ParticipantType::findOrFail(ParticipantType::QUIZMASTER);

        $tournamentQuizmaster->tournament_id = $tournament->id;
        $tournamentQuizmaster->user_id = Auth::user()->id;

        if ($request->get('group_id')) {
            $tournamentQuizmaster->group_id = $request->get('group_id');
        }

        /** @var QuizzingPreferences $quizzingPreferences */
        $quizzingPreferences = $tournamentQuizmaster->quizzing_preferences;
        $quizzingPreferences->setQuizzedAtThisTournamentBefore($request->get('quizzed_at_tournament'));
        $quizzingPreferences->setTimesQuizzedAtThisTournament($request->get('times_quizzed_at_tournament'));
        $quizzingPreferences->setGamesQuizzedThisSeason($request->get('games_quizzed_this_season'));
        $quizzingPreferences->setQuizzingInterest($request->get('quizzing_interest'));
        $tournamentQuizmaster->quizzing_preferences = $quizzingPreferences;
        $tournamentQuizmaster->save();
        
        $quizmasterRegistrationPaymentReceived->setTournamentQuizmaster($tournamentQuizmaster);

        // registrations with fees go to the cart
        $fee = $tournament->fee($participantType);
        if ($fee > 0) {
            $cart = Cart::clear();
            $cart->setPostPurchaseEvent($quizmasterRegistrationPaymentReceived)->save();

            $cart->add(
                QuizmasterRegistration::SKU,
                $fee,
                1
            );

            return redirect('/cart');
        }

        return $quizmasterRegistrationPaymentReceived->successStep();
    }
}
