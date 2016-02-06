<?php namespace BibleBowl\Http\Controllers;

use BibleBowl\Http\Requests\PaymentRequest;
use DB;
use BibleBowl\Cart;
use BibleBowl\Player;
use Illuminate\Http\Request;

/**
 * The main concept behind this controller is that other
 * areas of the site can build a "cart" that comes here
 * for the payment to be processed.  The cart contains
 * information about the purchase and information about
 * event(s) to take place after purchase.
 */
class ShopController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function viewCart()
    {
        return view('cart');
    }

    /**
     * @return mixed
     */
    public function processPayment(PaymentRequest $request)
    {
        $cart = Cart::current();
        $cart->triggerPostPurchaseEvent();

        return redirect('/dashboard')->withFlashSuccess($cart->postPurchaseEvent()->successMessage());
    }
}
