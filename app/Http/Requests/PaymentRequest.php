<?php namespace BibleBowl\Http\Requests;

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
            'stripeToken' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'stripeToken.required'  => 'Unable to process credit card information'
        ];
    }
}
