<?php namespace BibleBowl\Http\Requests\Groups;

use BibleBowl\Address;
use BibleBowl\Group;
use BibleBowl\Http\Requests\Request;
use Session;

class PlayerInactiveToggleRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Session::group()->players()->where('players.id', $this->route('player'))->count() > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

}
