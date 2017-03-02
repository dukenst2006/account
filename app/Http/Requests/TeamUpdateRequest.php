<?php

namespace App\Http\Requests;

use App\Team;
use Auth;
use Session;

class TeamUpdateRequest extends TeamGroupOnlyRequest
{
    protected $team;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->team = Team::findOrFail($this->route('team'));

        return Session::group()->id == $this->team->teamSet->group_id && $this->team->teamSet->canBeEdited(Auth::user());
    }

    public function team()
    {
        return $this->team;
    }
}
