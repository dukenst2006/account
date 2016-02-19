<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Role;
use Session;
use BibleBowl\Group;

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
        if (Auth::user()->hasRole(Role::HEAD_COACH)) {
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
