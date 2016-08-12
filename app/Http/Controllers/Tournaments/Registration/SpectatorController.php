<?php namespace BibleBowl\Http\Controllers\Tournaments\Registration;

use Auth;
use BibleBowl\Competition\Tournaments\Registration\SpectatorRegistrar;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistrar;
use BibleBowl\Competition\Tournaments\Registration\QuizmasterRegistration;
use BibleBowl\Competition\Tournaments\Registration\QuizzingPreferences;
use BibleBowl\Competition\Tournaments\Registration\SpectatorRegistrationPaymentReceived;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\Tournament\Registration\QuizmasterRegistrationRequest;
use BibleBowl\Http\Requests\Tournament\Registration\QuizzingPreferencesRequest;
use BibleBowl\Http\Requests\Tournament\Registration\SpectatorRegistrationRequest;
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
        SpectatorRegistrar $spectatorRegistrar
    ) {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $spectator = $spectatorRegistrar->register(
            $tournament,
            $request->except('_token'),
            Auth::user(),
            $request->get('group_id') ? Group::findOrFail($request->get('group_id')) : null
        );

        $spectatorRegistrationPaymentReceived->setSpectator($spectator);

        // registrations with fees go to the cart
        $fee = $tournament->fee($spectator->participant_type);
        if ($fee > 0) {
            $cart = Cart::clear();
            $cart->setPostPurchaseEvent($spectatorRegistrationPaymentReceived)->save();

            $cart->add(
                $spectator->sku(),
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
        SpectatorRegistrationRequest $request,
        $slug,
        SpectatorRegistrar $spectatorRegistrar
    ) {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $spectator = $spectatorRegistrar->register(
            $tournament,
            $request->except('_token'),
            $request->get('registering_as_current_user') == 1 ? Auth::user() : null,
            Session::group()
        );

        $registrationType = 'Adult';
        if ($spectator->isFamily()) {
            $registrationType = 'Family';
        }

        return redirect('/tournaments/'.$tournament->slug.'/group')->withFlashSuccess($registrationType.' has been added');
    }
}
