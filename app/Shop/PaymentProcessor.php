<?php

namespace BibleBowl\Shop;

use BibleBowl\Receipt;
use BibleBowl\User;
use DB;
use Illuminate\Support\Collection;
use Omnipay;

class PaymentProcessor
{
    private $receipt;

    /**
     * @param $token
     * @param $total
     * @param Collection $receiptItems
     * @param User       $user
     *
     * @return bool
     */
    public function pay($token, $total, Collection $receiptItems, User $user = null)
    {
        DB::beginTransaction();

        $receiptDetails = [
            'total' => $total,
        ];

        if ($user != null) {
            $receiptDetails['user_id'] = $user->id;
            $receiptDetails['address_id'] = $user->primary_address_id;
        }

        $order = Receipt::create($receiptDetails);
        $order->items()->saveMany($receiptItems);

        /** @var \Omnipay\Stripe\Message\Response $response */
        $response = Omnipay::purchase([
            'currency'  => 'USD',
            'amount'    => $total,
            'token'     => $token,
        ])->send();

        if ($response->isSuccessful()) {
            $order->update([
                'payment_reference_number' => $response->getTransactionReference(),
            ]);

            $this->receipt = $order;

            DB::commit();

            return true;
        }

        throw new PaymentFailed($response->getMessage());
    }

    public function receipt()
    {
        return $this->receipt;
    }
}
