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

        $receipt = Receipt::create($receiptDetails);
        $receipt->items()->saveMany($receiptItems);

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
        if ($user != null) {
            $chargeData['receipt_email'] = $user->email;
        }

        /** @var \Omnipay\Stripe\Message\Response $response */
        $response = Omnipay::purchase($chargeData)->send();

        if ($response->isSuccessful()) {
            $receipt->update([
                'payment_reference_number' => $response->getTransactionReference(),
            ]);

            $this->receipt = $receipt;

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
