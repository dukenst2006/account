<?php namespace BibleBowl\Http\Requests;

use BibleBowl\TeamSet;
use Session;

class TeamSetGroupOnlyRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Session::group()->id == TeamSet::findOrFail($this->route('teamsets'))->group_id;
    }

}
