<?php

namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Group;
use BibleBowl\Role;
use Session;

class GroupCreatorOnlyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $groupId = $this->route('group');
        if (Auth::user()->isA(Role::HEAD_COACH)) {
            $groupId = Session::group()->id;
        }

        return Group::where('id', $groupId)
            ->where('owner_id', Auth::id())
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
