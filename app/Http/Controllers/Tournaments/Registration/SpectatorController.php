<?php namespace BibleBowl\Http\Controllers\Tournaments\Registration;

use Auth;
use BibleBowl\Competition\Tournaments\Registration\AdultRegistrar;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistrar;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistration;
use BibleBowl\Competition\Tournaments\Registration\QuizzingPreferences;
use BibleBowl\Competition\Tournaments\Registration\SpectatorRegistrationPaymentReceived;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\Tournament\Registration\QuizmasterRegistrationRequest;
use BibleBowl\Http\Requests\Tournament\Registration\QuizzingPreferencesRequest;
use BibleBowl\Http\Requests\Tournament\Registration\StandaloneQuizmasterRegistrationRequest;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistrationPaymentReceived;
use BibleBowl\Http\Requests\Tournament\Registration\StandaloneSpectatorRegistrationRequest;
use BibleBowl\ParticipantType;
use BibleBowl\Tournament;
use BibleBowl\TournamentQuizmaster;
use Session;
use Cart;

class SpectatorController extends Controller
{

    public function getRegistration($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();
        $adultParticipantType = ParticipantType::findOrFail(ParticipantType::ADULT);
        $familyParticipantType = ParticipantType::findOrFail(ParticipantType::FAMILY);

        // determine if the head coach is registering this spectator
        if (Session::group() == null) {
            $view = 'tournaments.registration.standalone-spectator';
        } else {
            $view = 'tournaments.registration.headcoach-spectator';
        }

        return view($view, [
            'tournament'            => $tournament,
            'adultFee'              => $tournament->fee($adultParticipantType),
            'familyFee'             => $tournament->fee($familyParticipantType)
        ]);
    }

    /**
     * Standalone registration is a Spectator that is registering
     * themselves, not the Head Coach registering on their behalf.
     */
    public function postStandaloneRegistration(
        StandaloneSpectatorRegistrationRequest $request,
        $slug,
        SpectatorRegistrationPaymentReceived $spectatorRegistrationPaymentReceived,
        AdultRegistrar $adultRegistrar
    ) {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $adult = $adultRegistrar->register(
            $tournament,
            $request->except('_token'),
            Auth::user(),
            $request->get('group_id') ? Group::findOrFail($request->get('group_id')) : null
        );

        $spectatorRegistrationPaymentReceived->setSpectator($adult);

        // registrations with fees go to the cart
        $fee = $tournament->fee($adult->participant_type);
        if ($fee > 0) {
            $cart = Cart::clear();
            $cart->setPostPurchaseEvent($spectatorRegistrationPaymentReceived)->save();

            $cart->add(
                $adult->sku(),
                $fee,
                1
            );

            return redirect('/cart');
        }

        return $spectatorRegistrationPaymentReceived->successStep();
    }

    /**
     * Regular registrations are where the Head Coach registers on
     * behalf of the Adult/Family
     */
    public function postRegistration(
        QuizmasterRegistrationRequest $request,
        $slug,
        QuizmasterRegistrar $quizmasterRegistrar
    ) {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $quizmasterRegistrar->register(
            $tournament,
            $request->except('_token'),
            null,
            Session::group()
        );

        return redirect('/tournaments/'.$tournament->slug.'/group')->withFlashSuccess('Quizmaster has been added');
    }
}
