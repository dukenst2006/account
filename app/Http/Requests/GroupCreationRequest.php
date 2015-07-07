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
		return Group::validationRules();
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
