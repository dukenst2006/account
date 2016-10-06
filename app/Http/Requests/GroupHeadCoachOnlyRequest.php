<?php

namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Group;
use BibleBowl\Role;
use Illuminate\Database\Eloquent\Builder;

class GroupHeadCoachOnlyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->isNotA(Role::HEAD_COACH)) {
            return false;
        }

        $groupId = $this->route('group');

        return Group::where('id', $groupId)
            ->whereHas('users', function (Builder $q) {
                $q->where('users.id', Auth::user()->id);
            })
            ->exists();
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
