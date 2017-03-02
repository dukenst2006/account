<?php

namespace App\Http\Requests\Tournament\Registration;

use App\Http\Requests\Request;
use App\Tournament;

class QuizzingPreferencesRequest extends Request
{
    /** @var Tournament */
    protected $tournament;

    public function tournament() : Tournament
    {
        return $this->tournament;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->tournament = Tournament::where('slug', $this->route('slug'))->first();

        if ($this->tournament->settings->shouldCollectQuizmasterPreferences()) {
            return [
                'quizzed_at_tournament'         => 'required',
                'times_quizzed_at_tournament'   => 'required_if:quizzed_at_tournament,1',
                'games_quizzed_this_season'     => 'required',
                'quizzing_interest'             => 'required',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'quizzed_at_tournament.required'        => "Please tell us whether you've ever quizzed at this tournament",
            'times_quizzed_at_tournament.required'  => "Please tell us how many times you've quizzed at this tournament",
            'games_quizzed_this_season.required'    => "Please tell us how many games you've quizzed this season",
            'quizzing_interest.required'            => 'Please tell us what your quizzing interest in',
        ];
    }
}
