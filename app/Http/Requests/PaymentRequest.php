<?php

namespace App\Http\Requests;

class PaymentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stripeToken' => 'required_without:payment_reference_number',
        ];
    }

    public function messages()
    {
        return [
            'stripeToken.required_unless'  => 'Unable to process credit card information',
        ];
    }
}
