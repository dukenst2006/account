<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Shop\PaymentFailed;
use App\Shop\PaymentProcessor;
use Auth;
use Cart;
use DB;

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
            $receipt = $paymentProcessor->createReceipt(
                Cart::total(),
                Cart::receiptItems(),
                Auth::user() ?? Auth::user()
            );

            // admins can provide a payment reference number to bypass credit card charges
            if ($request->has('payment_reference_number')) {
                $transactionId = $request->get('payment_reference_number');
                $receipt->update([
                    'payment_reference_number' => $request->get('payment_reference_number'),
                ]);
            } else {
                $transactionId = $paymentProcessor->pay(
                    $request->input('stripeToken'),
                    Cart::total(),
                    $receipt
                );
            }

            if ($transactionId) {
                $postPurchaseEvent->fire($receipt);
            }
            DB::commit();
        } catch (PaymentFailed $e) {
            DB::rollBack();

            return redirect()->back()->withErrors($e->getMessage());
        }

        return $postPurchaseEvent->successStep();
    }
}
