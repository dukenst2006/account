<?php namespace BibleBowl\Http\Requests;

trait IncludesCreditCardPayment
{
    public function creditCardNumber()
    {
        return $this->input('cardNumber');
    }

    public function creditCardCVV()
    {
        return $this->input('cardCVV');
    }

    public function creditCardExpireMonth()
    {
        return $this->input('cardExpireMonth');
    }

    public function creditCardExpireYear()
    {
        return $this->input('cardExpireYear');
    }
}
