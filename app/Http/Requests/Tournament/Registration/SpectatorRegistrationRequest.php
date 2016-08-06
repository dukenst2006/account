<?php namespace BibleBowl\Http\Requests\Tournament\Registration;

use Auth;
use BibleBowl\Http\Requests\Request;

class SpectatorRegistrationRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required',
            'gender'                => 'required',
            'shirt_size'            => 'required',

            'spouse_gender'         => 'required_with:spouse_first_name',
            'spouse_shirt_size'     => 'required_with:spouse_first_name',

            'minor.*.shirt_size'    => 'required_with:minor.*.first_name',
            'minor.*.age'           => 'required_with:minor.*.age',
            'minor.*.gender'        => 'required_with:minor.*.gender',
        ];
    }

    public function messages()
    {
        return [
            'spouse_gender.required'        => "Your spouse's gender is required",
            'spouse_shirt_size.required'    => "Your spouse's shirt size is required",
        ];
    }
}