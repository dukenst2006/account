<?php

namespace App\Http\Requests;

use App\TeamSet;
use Auth;
use Session;

class TeamSetUpdateRequest extends TeamSetGroupOnlyRequest
{
    /** @var TeamSet */
    protected $teamSet;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->teamSet = TeamSet::findOrFail($this->route('teamset'));

        return Session::group()->id == $this->teamSet->group_id && $this->teamSet->canBeEdited(Auth::user());
    }

    public function teamSet() : TeamSet
    {
        return $this->teamSet;
    }
}
