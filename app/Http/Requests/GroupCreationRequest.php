<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Address;
use BibleBowl\Group;

class GroupCreationRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(Group::validationRules(), [
                'amHeadCoach' => 'required'
            ]);
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return array_merge(Group::validationMessages(), [
            'amHeadCoach.required' => 'Only the Head Coach may create this group'
        ]);
    }

    /**
     * @return array
     */
    public function all()
    {
        // merge it in directly rather than using
        // a hidden form field
        $this->merge([
            'owner_id' => Auth::user()->id
        ]);

        return parent::all();
    }

}
