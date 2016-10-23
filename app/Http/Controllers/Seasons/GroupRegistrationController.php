<?php

namespace BibleBowl\Http\Controllers\Seasons;

use BibleBowl\Group;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupHeadCoachOnlyRequest;
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

    /**
     * Build the shopping cart for registering these players.
     */
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
