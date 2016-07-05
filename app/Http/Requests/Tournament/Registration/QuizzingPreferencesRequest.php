<?php namespace BibleBowl\Http\Requests\Tournament\Registration;

use BibleBowl\Group;
use BibleBowl\Role;
use BibleBowl\Http\Requests\Request;

class QuizzingPreferencesRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quizzed_at_tournament'         => 'required',
            'times_quizzed_at_tournament'   => 'required_if:quizzed_at_tournament,1',
            'games_quizzed_this_season'     => 'required',
            'quizzing_interest'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            'quizzed_at_tournament.required'        => "Please tell us whether you've ever quizzed at this tournament",
            'times_quizzed_at_tournament.required'  => "Please tell us how many times you've quizzed at this tournament",
            'games_quizzed_this_season.required'    => "Please tell us how many games you've quizzed this season",
            'quizzing_interest.required'            => "Please tell us what your quizzing interest in"
        ];
    }
}
