<?php

namespace App\Http\Controllers\Seasons;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupHeadCoachOnlyRequest;
use App\Player;
use App\Role;
use App\Season;
use App\Seasons\ProgramRegistrationPaymentReceived;
use Auth;
use Cart;
use Illuminate\View\View;
use Session;
use Setting;

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

        // Split the cart into discount and regular registrations
        if (Setting::firstYearDiscount() > 0) {
            $firstYearPlayerCount = Player::whereIn('id', array_keys($request->get('player')))->has('seasons', '=', 1)->count();

            if ($firstYearPlayerCount > 0) {
                $cart->add(
                    $group->program->sku.'_FIRST_YEAR',
                    $group->program->registration_fee * (Setting::firstYearDiscount() / 100),
                    $firstYearPlayerCount
                );
            }

            $returningPlayerCount = count($request->get('player')) - $firstYearPlayerCount;
            if ($returningPlayerCount > 0) {
                $cart->add(
                    $group->program->sku,
                    $group->program->registration_fee,
                    $returningPlayerCount
                );
            }
        } else {
            $cart->add(
                $group->program->sku,
                $group->program->registration_fee,
                count($request->get('player'))
            );
        }

        return redirect('/cart');
    }
}
