<?php namespace BibleBowl\Http\Requests\Groups;

use BibleBowl\Group;
use BibleBowl\Http\Requests\Request;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class UserInviteRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}
