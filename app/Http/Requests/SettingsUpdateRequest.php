<?php namespace BibleBowl\Http\Requests;

use BibleBowl\Permission;
use Auth;

class SettingsUpdateRequest extends GroupJoinRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can(Permission::MANAGE_SETTINGS);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'season_end'    => 'required',
            //'program.*.fee' => 'required' @todo Uncomment this
        ];
    }

    public function messages()
    {
        return [
            'program.*.fee.required' => 'One of the programs is missing a fee'
        ];
    }
}
