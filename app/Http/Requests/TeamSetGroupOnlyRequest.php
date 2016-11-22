<?php

namespace BibleBowl\Http\Requests;

use BibleBowl\TeamSet;
use Session;

class TeamSetGroupOnlyRequest extends Request
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

        return Session::group()->id == $this->teamSet->group_id;
    }

    public function teamSet() : TeamSet
    {
        return $this->teamSet;
    }
}
