<?php

namespace App\Http\Requests\Groups;

use App\Group;
use App\Http\Requests\Request;
use Auth;
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
