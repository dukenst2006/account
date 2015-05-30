<?php namespace BibleBowl\Http\Requests;

use Auth;
use BibleBowl\Address;
use BibleBowl\Http\Requests\Request;
use BibleBowl\Player;

class GuardianOnlyRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Player::where('id', $this->route('player'))
			->where('guardian_id', Auth::id())
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
