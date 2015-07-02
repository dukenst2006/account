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
        $rules = Group::validationRules();
        //require either used owned address
        //or address fields
        if ($this->get('user_owned_address', 0) != 1) {
            // pull all validation rules except the name
            $addressRules = array_except(
                Address::validationRules(),
                [
                    'name'
                ]
            );
            $rules = array_merge($rules, $addressRules);

            unset($rules['address_id']);
        }

		return $rules;
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
