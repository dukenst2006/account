<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Permission;

class TournamentCreateRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can(Permission::CREATE_TOURNAMENTS);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required',
            'start'                 => 'required',
            'end'                   => 'required',
            'registration_start'    => 'required',
            'registration_end'      => 'required',
            'url'                   => 'url'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start.required'                => 'A start date is required',
            'end.required'                  => 'An end date is required',
            'registration_start.required'   => 'A registration start date is required',
            'registration_end.required'     => 'A registration end date is required',
            'url.url'                       => 'url'
        ];
    }
}
