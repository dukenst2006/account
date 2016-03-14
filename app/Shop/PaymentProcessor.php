<?php

namespace BibleBowl\Shop;

use BibleBowl\Receipt;
use BibleBowl\User;
use DB;
use Illuminate\Support\Collection;
use Omnipay;

class PaymentProcessor
{
    /**
     * @param User $user
     * @param $token
     * @param $total
     * @param Collection $receiptItems
     * @return bool
     */
    public function pay(User $user, $token, $total, Collection $receiptItems)
    {
        DB::beginTransaction();

        $order = Receipt::create([
            'total'         => $total,
            'user_id'       => $user->id,
            'address_id'    => $user->primary_address_id
        ]);
        $order->items()->saveMany($receiptItems);

        /** @var \Omnipay\Stripe\Message\Response $response */
        $response = Omnipay::purchase([
            'currency'  => 'USD',
            'amount'    => $total,
            'token'     => $token
        ])->send();

        if ($response->isSuccessful()) {
            $order->update([
                'payment_reference_number' => $response->getTransactionReference()
            ]);

            DB::commit();

            return true;
        }

        throw new PaymentFailed($response->getMessage());
    }
}