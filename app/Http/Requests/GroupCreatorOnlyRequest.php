<?php namespace BibleBowl\Http\Requests;

use Auth;
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
        return Group::where('id', $this->route('group'))
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
