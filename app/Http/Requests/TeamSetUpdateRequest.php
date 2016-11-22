<?php

namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\TeamSet;
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
