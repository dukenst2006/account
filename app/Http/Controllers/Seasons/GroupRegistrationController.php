<?php

namespace BibleBowl\Http\Controllers\Seasons;

use Auth;
use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupHeadCoachOnlyRequest;
use BibleBowl\Role;
use BibleBowl\Season;
use BibleBowl\Seasons\ProgramRegistrationPaymentReceived;
use Cart;
use Illuminate\View\View;
use Session;

class GroupRegistrationController extends Controller
{
    /**
     * Allow the group to choose which players to
     * pay the registration for.
     *
     * @return View
     */
    public function getPayPlayerRegistration()
    {
        // Sometimes Head Coaches forward the pay email to the parent, leading them to this page and seeing an error
        if (Auth::user()->isA(Role::HEAD_COACH) === false) {
            return redirect('/dashboard')->withFlashError('Please submit your seasonal registration fee to the Head Coach of your group, and they will pay collectively for the group');
        }

        $season = Session::season();
        $group = Session::group();

        return view('seasons.registration.pay', [
            'group'   => $group,
            'players' => $group->players()
                ->pendingRegistrationPayment($season)
                ->active($season)
                ->get(),
        ]);
    }

    public function postPayPlayerRegistration(GroupHeadCoachOnlyRequest $request, ProgramRegistrationPaymentReceived $programRegistrationPaymentReceived)
    {
        $this->validate($request, [
            'player' => 'required',
        ], [
            'player.required' => 'You must select at least one player to proceed',
        ]);

        $programRegistrationPaymentReceived->setPlayers(collect(array_keys($request->get('player'))));

        $cart = Cart::clear();
        $cart->setPostPurchaseEvent($programRegistrationPaymentReceived)->save();

        $group = Session::group();
        $cart->add(
            $group->program->sku,
            $group->program->registration_fee,
            count($request->get('player'))
        );

        return redirect('/cart');
    }
}
