<?php

namespace App\Http\Requests;

use App\Ability;
use Bouncer;

class SettingsUpdateRequest extends GroupJoinRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Bouncer::allows(Ability::MANAGE_SETTINGS);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'season_end'                    => 'required',
            'program.*.registration_fee'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'program.*.fee.registration_fee' => 'One of the programs is missing a fee',
        ];
    }
}
