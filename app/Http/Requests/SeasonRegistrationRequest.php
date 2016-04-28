<?php namespace BibleBowl\Http\Requests;

use Illuminate\Contracts\Validation\Factory;

class SeasonRegistrationRequest extends GroupJoinRequest
{

    public function __construct(Factory $factory)
    {
        $factory->extend('required_one', function ($attribute, $value, $parameters) {
            return count(array_column($this->request->get($attribute), 'register')) > 0;
        });
    }

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
        $rules['player'] = 'required_one';
        foreach ($this->request->get('player') as $playerId => $playerData) {
            $rules['player.'.$playerId.'.grade'] = 'required|min:1';
            $rules['player.'.$playerId.'.shirtSize'] = 'required|min:1';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages['player.required_one'] = 'You must select a player to register';
        foreach ($this->request->get('player') as $playerId => $playerData) {
            // only throw other validation errors if the player was checked
            if (isset($playerData['registered']) && $playerData['registered'] == 1) {
                $messages['player.'.$playerId.'.grade.required'] = 'One or more of your players is missing a grade';
                $messages['player.'.$playerId.'.grade.min'] = 'One or more of your players is missing a grade';
                $messages['player.'.$playerId.'.shirtSize.required'] = 'One or more of your players is missing a t-shirt size';
                $messages['player.'.$playerId.'.shirtSize.min'] = 'One or more of your players is missing a t-shirt size';
            }
        }
        return $messages;
    }
}
