<?php

namespace BibleBowl\Http\Requests\Tournament\Registration;

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

            'first_name'            => 'required_unless:registering_as_current_user,1|max:32',
            'last_name'             => 'required_unless:registering_as_current_user,1|max:32',
            'email'                 => 'required_unless:registering_as_current_user,1|email|max:128',
            'gender'                => 'required_unless:registering_as_current_user,1',
        ];

        // add address validation rules with a condition
        $addressRules = array_except(Address::validationRules(), ['name']);
        foreach ($addressRules as $field => $rule) {
            $rules[$field] = str_replace('required', 'required_unless:registering_as_current_user,1', $rule);
        }

        return $rules;
    }

    public function messages()
    {
        return array_merge([
            'first_name.required_unless'    => 'The first name field is required',
            'last_name.required_unless'     => 'The last name field is required',
            'email.required_unless'         => 'The email field is required',
            'gender.required_unless'        => 'The gender field is required',

            'address_one.required_unless'   => 'The street address field is required',
            'zip_code.required_unless'      => 'The zip code field is required',

            'spouse_gender.required'        => "Your spouse's gender is required",
            'spouse_shirt_size.required'    => "Your spouse's shirt size is required",
        ], Address::validationMessages());
    }
}
