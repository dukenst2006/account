<?php namespace BibleBowl\Http\Requests\Tournament\Registration;

use Auth;
use BibleBowl\Http\Requests\Request;

class StandaloneSpectatorRegistrationRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'shirt_size'            => 'required',

            'spouse_gender'         => 'required_with:spouse_first_name',
            'spouse_shirt_size'     => 'required_with:spouse_first_name',
        ];

        if (Auth::user() === null) {
            $rules['first_name']    = 'required';
            $rules['last_name']     = 'required';
            $rules['email']         = 'required';
            $rules['gender']        = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'spouse_gender.required'        => "Your spouse's gender is required",
            'spouse_shirt_size.required'    => "Your spouse's shirt size is required",
        ];
    }
}