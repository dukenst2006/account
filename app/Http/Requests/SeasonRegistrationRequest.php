<?php namespace BibleBowl\Http\Requests;

class SeasonRegistrationRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        $rules = [];

        foreach($this->request->get('player') as $playerId => $playerData) {
            $rules['player.'.$playerId.'.grade'] = 'required|min:1';
            $rules['player.'.$playerId.'.shirt_size'] = 'required|min:1';
        }

		return $rules;
	}

    public function messages()
    {
        $messages = [];
        foreach($this->request->get('player') as $playerId => $playerData)
        {
            $messages['player.'.$playerId.'.grade.min'] = 'One or more of your players is missing a grade';
            $messages['player.'.$playerId.'.shirt_size.min'] = 'One or more of your players is missing a t-shirt size';
        }
        return $messages;
    }

}
