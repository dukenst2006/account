<?php

namespace BibleBowl\Http\Controllers\Tournaments\Registration;

use Auth;
use BibleBowl\Competition\Tournaments\Quizmasters\QuizzingPreferences;
use BibleBowl\Competition\Tournaments\Quizmasters\Registrar;
use BibleBowl\Competition\Tournaments\Quizmasters\RegistrationConfirmation;
use BibleBowl\Competition\Tournaments\Quizmasters\RegistrationPaymentReceived;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\HeadCoachOnlyRequest;
use BibleBowl\Http\Requests\Tournament\Registration\QuizmasterRegistrationRequest;
use BibleBowl\Http\Requests\Tournament\Registration\QuizzingPreferencesRequest;
use BibleBowl\Http\Requests\Tournament\Registration\StandaloneQuizmasterRegistrationRequest;
use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use Cart;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Session;

class QuizmasterController extends Controller
{
    public function getRegistration($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        // determine if the head coach is registering this quizmaster
        if (Session::group() == null) {
            if ($tournament->whereHas('tournamentQuizmasters', function (Builder $q) {
                $q->where('user_id', Auth::user()->id);
            })->count() > 0) {
                return redirect()->back()->withErrors("You've already registered for this tournament");
            }

            $view = 'tournaments.registration.standalone-quizmaster';
        } else {
            $groups = null;
            $view = 'tournaments.registration.headcoach-quizmaster';
        }

        $fee = $tournament->fee(ParticipantType::QUIZMASTER);

        return view($view, [
            'tournament'            => $tournament,
            'hasFee'                => $fee > 0,
            'fee'                   => $fee,
            'quizzingPreferences'   => app(QuizzingPreferences::class),
        ]);
    }

    /**
     * Standalone registration is a Quizmaster that is registering
     * themselves, not the Head Coach registering on their behalf.
     */
    public function postStandaloneRegistration(
        StandaloneQuizmasterRegistrationRequest $request,
        $slug,
        RegistrationPaymentReceived $quizmasterRegistrationPaymentReceived,
        Registrar $quizmasterRegistrar
    ) {
        $tournament = $request->tournament();

        DB::beginTransaction();

        $tournamentQuizmaster = $quizmasterRegistrar->register(
            $tournament,
            $request->except('_token'),
            Auth::user(),
            $request->get('group_id') ? Group::findOrFail($request->get('group_id')) : null
        );

        $quizmasterRegistrationPaymentReceived->setTournamentQuizmaster($tournamentQuizmaster);

        // registrations with fees go to the cart
        $fee = $tournament->fee(ParticipantType::QUIZMASTER);
        if ($fee > 0) {
            $cart = Cart::clear();
            $cart->setPostPurchaseEvent($quizmasterRegistrationPaymentReceived)->save();

            $cart->add(
                ParticipantType::sku($tournament, ParticipantType::QUIZMASTER),
                $fee,
                1
            );

            DB::commit();

            return redirect('/cart');
        }

        // no fees, so do the deed and get on it with it
        $quizmasterRegistrationPaymentReceived->fire();

        DB::commit();

        return $quizmasterRegistrationPaymentReceived->successStep();
    }

    /**
     * Regular registrations are where the Head Coach registers on
     * behalf of the Quizmaster.
     */
    public function postRegistration(
        QuizmasterRegistrationRequest $request,
        $slug,
        Registrar $quizmasterRegistrar
    ) {
        $tournament = $request->tournament();

        DB::beginTransaction();

        $quizmaster = $quizmasterRegistrar->register(
            $tournament,
            $request->except('_token'),
            null,
            Session::group(),
            Auth::user() != null ? Auth::user() : null
        );

        $redirectUrl = '/tournaments/'.$tournament->slug.'/group';
        if ($request->has('save-and-add')) {
            $redirectUrl = '/tournaments/'.$tournament->slug.'/registration/quizmaster';
        }

        if ($tournament->hasFee(ParticipantType::QUIZMASTER) === false) {
            $quizmaster->notify(new RegistrationConfirmation());
        }

        DB::commit();

        return redirect($redirectUrl)->withFlashSuccess('Quizmaster has been added');
    }

    public function getPreferences($slug, $guid)
    {
        /** @var TournamentQuizmaster $quimaster */
        $quimaster = TournamentQuizmaster::where('guid', $guid)->firstOrFail();

        return view('tournaments.registration.set-quizzing-preferences', [
            'tournament'            => $quimaster->tournament,
            'group'                 => $quimaster->group,
            'quizmaster'            => $quimaster,
            'quizzingPreferences'   => $quimaster->quizzing_preferences,
        ]);
    }

    public function postPreferences(QuizzingPreferencesRequest $request, $slug, $guid)
    {
        /** @var TournamentQuizmaster $tournamentQuizmaster */
        $tournamentQuizmaster = TournamentQuizmaster::where('guid', $guid)->firstOrFail();

        // model events will pick this up and make sure this user
        // is flagged as a quizmaster in the mailing list
        if ($tournamentQuizmaster->user_id == null && $tournamentQuizmaster->email == Auth::user()->email) {
            $tournamentQuizmaster->user_id = Auth::user()->id;
        }

        /** @var QuizzingPreferences $quizzingPreferences */
        $quizzingPreferences = $tournamentQuizmaster->quizzing_preferences;
        $quizzingPreferences->setQuizzedAtThisTournamentBefore($request->get('quizzed_at_tournament'));
        $quizzingPreferences->setTimesQuizzedAtThisTournament($request->get('times_quizzed_at_tournament'));
        $quizzingPreferences->setGamesQuizzedThisSeason($request->get('games_quizzed_this_season'));
        $quizzingPreferences->setQuizzingInterest($request->get('quizzing_interest'));
        $tournamentQuizmaster->quizzing_preferences = $quizzingPreferences;
        $tournamentQuizmaster->shirt_size = $request->get('shirt_size');
        $tournamentQuizmaster->save();

        return redirect('/tournaments/'.$tournamentQuizmaster->tournament->slug)->withFlashSuccess('Your quizzing preferences have been updated');
    }

    public function deleteRegistration(HeadCoachOnlyRequest $request, $slug, $guid)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $quizmaster = TournamentQuizmaster::where('guid', $guid)->firstOrFail();
        $quizmaster->delete();

        return redirect('/tournaments/'.$tournament->slug.'/group')->withFlashSuccess($quizmaster->full_name.' has been removed');
    }
}
