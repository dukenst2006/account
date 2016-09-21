<?php

namespace BibleBowl\Http\Requests;

class SeasonRegistrationRequest extends GroupJoinRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'terms_of_participation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'terms_of_participation.required' => 'You must agree to the Terms of Participation',
        ];
    }
}
