<?php

namespace App\Http\Requests;

use App\Group;
use App\Role;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Session;

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
        if ($groupId == null) {
            $groupId = Session::group()->id;
        }

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
