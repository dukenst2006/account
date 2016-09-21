<?php

namespace BibleBowl\Http\Requests;

use BibleBowl\Team;
use Session;

class TeamGroupOnlyRequest extends Request
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

        return Session::group()->id == $this->team->teamSet->group_id;
    }

    public function team()
    {
        return $this->team;
    }
}
