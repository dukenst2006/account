<?php namespace BibleBowl\Http\Requests;

use BibleBowl\Address;
use BibleBowl\Group;

class GroupEditRequest extends GroupCreatorOnlyRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array_except(
            Group::validationRules(),
            [
                'program_id',
                'owner_id'
            ]
        );
	}

}
