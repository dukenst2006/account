<?php

namespace BibleBowl\Http\Requests\Groups;

use Auth;
use BibleBowl\Group;
use BibleBowl\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;

class RemoveUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Group::whereHas('users', function (Builder $q) {
            $q->where('id', Auth::user()->id);
        })->where('id', $this->route('group'))->count() > 0;
    }
}
