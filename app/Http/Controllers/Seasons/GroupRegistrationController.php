<?php namespace BibleBowl\Http\Controllers\Seasons;

use Auth;
use BibleBowl\Group;
use BibleBowl\Groups\GroupRegistrar;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Http\Requests\GroupCreatorOnlyRequest;
use BibleBowl\Http\Requests\GroupJoinRequest;
use BibleBowl\Http\Requests\PlayerRegistrationRequest;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Http\Requests\SeasonRegistrationRequest;
use BibleBowl\Program;
use BibleBowl\Season;
use BibleBowl\Seasons\ProgramRegistrationPaymentReceived;
use BibleBowl\Seasons\GroupRegistration;
use BibleBowl\Seasons\SeasonalRegistrationPaymentReceived;
use Illuminate\View\View;
use Input;
use Session;
use Cart;

class GroupRegistrationController extends Controller
{

    /**
     * Allow the group to choose which players to
     * pay the registration for
     *
     * @return View
     */
    public function getPayPlayerRegistration()
    {
        $group = Session::group();
        return view('seasons.registration.pay', [
            'group' => $group,
            'players' => $group->players()->pendingRegistrationPayment()->get()
        ]);
    }

    /**
     * Build the shopping cart for registering these players
     *
     * @param GroupCreatorOnlyRequest $request
     * @param ProgramRegistrationPaymentReceived $programRegistrationPaymentReceived
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postPayPlayerRegistration(GroupCreatorOnlyRequest $request, ProgramRegistrationPaymentReceived $programRegistrationPaymentReceived)
    {
        $this->validate($request, [
            'player' => 'required'
        ], [
            'player.required' => 'You must select at least one player to proceed'
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
