<?php namespace BibleBowl\Http\Requests\Tournament\Registration;

use Auth;
use BibleBowl\Address;
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

            'minor.*.shirt_size'    => 'required_with:minor.*.first_name',
            'minor.*.age'           => 'required_with:minor.*.age',
            'minor.*.gender'        => 'required_with:minor.*.gender',

            'first_name'            => 'required_if:registering_as_current_user,0|max:32',
            'last_name'             => 'required_if:registering_as_current_user,0|max:32',
            'email'                 => 'required_if:registering_as_current_user,0|email|max:128',
            'gender'                => 'required_if:registering_as_current_user,0'
        ];

        // add address validation rules with a condition
        $addressRules = array_except(Address::validationRules(), ['name']);
        foreach ($addressRules as $field => $rule) {
            $rules[$field] = str_replace('required', 'required_if:registering_as_current_user,0', $rule);
        }

        return $rules;
    }

    public function messages()
    {
        return array_merge([
            'first_name.required_if'        => "The first name field is required",
            'last_name.required_if'         => "The last name field is required",
            'email.required_if'             => "The email field is required",
            'gender.required_if'            => "The gender field is required",
            
            'address_one.required_if'       => "The street address field is required",
            'zip_code.required_if'          => "The zip code field is required",
            
            'spouse_gender.required'        => "Your spouse's gender is required",
            'spouse_shirt_size.required'    => "Your spouse's shirt size is required",
        ], Address::validationMessages());
    }
}