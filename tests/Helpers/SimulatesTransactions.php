<?php namespace Helpers;

use Omnipay\Stripe\Message\PurchaseRequest;
use Omnipay\Stripe\Message\Response;
use Omnipay;
use Mockery;

trait SimulatesTransactions
{

    /**
     * Make sure our tests never actually hit the payment
     * provider's server
     *
     * @return $transactionId
     */
    public function simulateTransaction() {
        $transactionId = uniqid();

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('isSuccessful')->andReturn(true);
        $response->shouldReceive('getTransactionReference')->andReturn($transactionId);
        $purchaseRequest = Mockery::mock(PurchaseRequest::class);
        $purchaseRequest->shouldReceive('send')->andReturn($response);
        Omnipay::shouldReceive('purchase')->andReturn($purchaseRequest);

        return $transactionId;
    }
}
