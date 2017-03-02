<?php

namespace App\Http\Controllers\Tournaments\Registration;

use App\Competition\Tournaments\Spectators\Registrar;
use App\Competition\Tournaments\Spectators\RegistrationConfirmation;
use App\Competition\Tournaments\Spectators\RegistrationPaymentReceived;
use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeadCoachOnlyRequest;
use App\Http\Requests\Tournament\Registration\SpectatorRegistrationRequest;
use App\Http\Requests\Tournament\Registration\StandaloneSpectatorRegistrationRequest;
use App\ParticipantType;
use App\Spectator;
use App\Tournament;
use Auth;
use Cart;
use DB;
use Session;

class SpectatorController extends Controller
{
    public function getRegistration($slug)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        // determine if the head coach is registering this spectator
        if (Session::group() == null) {
            $view = 'tournaments.registration.standalone-spectator';
        } else {
            $view = 'tournaments.registration.headcoach-spectator';
        }

        return view($view, [
            'tournament'            => $tournament,
            'adultFee'              => $tournament->fee(ParticipantType::ADULT),
            'familyFee'             => $tournament->fee(ParticipantType::FAMILY),
        ]);
    }

    /**
     * Standalone registration is a Spectator that is registering
     * themselves, not the Head Coach registering on their behalf.
     */
    public function postStandaloneRegistration(
        StandaloneSpectatorRegistrationRequest $request,
        RegistrationPaymentReceived $spectatorRegistrationPaymentReceived,
        Registrar $spectatorRegistrar
    ) {
        DB::beginTransaction();

        $spectator = $spectatorRegistrar->register(
            $request->tournament(),
            $request->except('_token'),
            Auth::user(),
            $request->get('group_id') ? Group::findOrFail($request->get('group_id')) : null
        );

        $spectatorRegistrationPaymentReceived->setSpectator($spectator);

        // registrations with fees go to the cart
        $fee = $request->tournament()->fee($spectator->participant_type->id);
        if ($fee > 0) {
            $cart = Cart::clear();
            $cart->setPostPurchaseEvent($spectatorRegistrationPaymentReceived)->save();

            $cart->add(
                ParticipantType::sku($request->tournament(), $spectator->participant_type->id),
                $fee,
                1
            );

            DB::commit();

            return redirect('/cart');
        }

        $spectator->notify(new RegistrationConfirmation());

        DB::commit();

        return $spectatorRegistrationPaymentReceived->successStep();
    }

    /**
     * Regular registrations are where the Head Coach registers on
     * behalf of the Adult/Family.
     */
    public function postRegistration(
        SpectatorRegistrationRequest $request,
        Registrar $spectatorRegistrar
    ) {
        DB::beginTransaction();

        $spectator = $spectatorRegistrar->register(
            $request->tournament(),
            $request->except('_token'),
            $request->get('registering_as_current_user') == 1 ? Auth::user() : null,
            Session::group(),
            Auth::user()
        );

        $redirectUrl = '/tournaments/'.$request->tournament()->slug.'/group';
        if ($request->has('save-and-add')) {
            $redirectUrl = '/tournaments/'.$request->tournament()->slug.'/registration/spectator';
        }

        if ($request->tournament()->hasFee(ParticipantType::ADULT) === false && $spectator->isAdult()) {
            $spectator->notify(new RegistrationConfirmation());
        } elseif ($request->tournament()->hasFee(ParticipantType::FAMILY) === false && $spectator->isFamily()) {
            $spectator->notify(new RegistrationConfirmation());
        }

        DB::commit();

        return redirect($redirectUrl)->withFlashSuccess($spectator->type().' has been added');
    }

    public function deleteRegistration(HeadCoachOnlyRequest $request, $slug, $guid)
    {
        $tournament = Tournament::where('slug', $slug)->firstOrFail();

        $spectator = Spectator::where('guid', $guid)->firstOrFail();

        DB::transaction(function () use ($spectator) {
            $spectator->minors()->delete();
            $spectator->delete();
        });

        return redirect('/tournaments/'.$tournament->slug.'/group')->withFlashSuccess($spectator->full_name.' has been removed');
    }
}
