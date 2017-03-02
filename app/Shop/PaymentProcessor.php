<?php

namespace App\Shop;

use App\Receipt;
use App\User;
use DB;
use Illuminate\Support\Collection;
use Omnipay;

class PaymentProcessor
{
    public function pay(string $token, $total, Receipt $receipt) : bool
    {
        DB::beginTransaction();

        $chargeData = [
            'currency'      => 'USD',
            'amount'        => $total,
            'token'         => $token,
            'order'         => $receipt->id,

            // attempt to include something itemized in the email
            'description'   => view('store.php-receipt', [
                'receiptItems' => $receipt->items,
            ]),

            'metadata' => [
                'receiptId' => $receipt->id,
            ],
        ];

        // allow Stripe to send receipt emails for us
        if ($receipt->user != null) {
            $chargeData['receipt_email'] = $receipt->user->email;
        }

        /** @var \Omnipay\Stripe\Message\Response $response */
        $response = Omnipay::purchase($chargeData)->send();

        if ($response->isSuccessful()) {
            $receipt->update([
                'payment_reference_number' => $response->getTransactionReference(),
            ]);

            DB::commit();

            return true;
        }

        throw new PaymentFailed($response->getMessage());
    }

    public function createReceipt($total, Collection $receiptItems, User $user = null) : Receipt
    {
        $receiptDetails = [
            'total' => $total,
        ];

        if ($user != null) {
            $receiptDetails['user_id'] = $user->id;
            $receiptDetails['address_id'] = $user->primary_address_id;
        }

        $receipt = Receipt::create($receiptDetails);
        $receipt->items()->saveMany($receiptItems);

        return $receipt;
    }
}
