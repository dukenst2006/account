<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Http\Requests\PaymentRequest;
use BibleBowl\Player;
use BibleBowl\Shop\PaymentFailed;
use BibleBowl\Shop\PaymentProcessor;
use Cart;
use DB;
use Omnipay;

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
    public function processPayment(PaymentRequest $request, PaymentProcessor $paymentProcessor)
    {
        $postPurchaseEvent = Cart::postPurchaseEvent();

        DB::beginTransaction();

        try {
            if ($transactionId = $paymentProcessor->pay(
                Auth::user(),
                $request->input('stripeToken'),
                Cart::total(),
                Cart::receiptItems()
            )) {
                $postPurchaseEvent->fire($paymentProcessor->receipt());
            }
            DB::commit();
        } catch (PaymentFailed $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }


        return $postPurchaseEvent->successStep();
    }
}
