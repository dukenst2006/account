<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Address;

class AddressOwnerOnlyRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Address::where('id', $this->route('address'))
			->where('user_id', Auth::id())
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
