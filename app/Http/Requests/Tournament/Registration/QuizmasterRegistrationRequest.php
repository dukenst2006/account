<?php namespace BibleBowl\Http\Requests\Tournament\Registration;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\Http\Requests\Request;

class QuizmasterRegistrationRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'   => 'First name is required',
            'last_name.required'    => 'Last name is required',
            'email.required'        => 'Email address is required',
            'email.email'           => 'Must be a valid email address'
        ];
    }
}
